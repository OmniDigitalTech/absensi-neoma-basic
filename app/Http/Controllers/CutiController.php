<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Mail\ApprovalCutiMail;
use App\Models\Cuti;
use App\Models\DataCuti;
use App\Models\Jabatan;
use App\Models\settings;
use App\Models\Shift;
use App\Models\User;
use App\Models\MappingShift;
use App\Services\EmailService;
use App\Services\KaryawanService;
use App\Services\NotifyService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Events\NotifApproval;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CutiController extends Controller
{
    protected $notifyService;
    protected $emailService;
    protected $cutiService;

    public function __construct(NotifyService $notifyService, EmailService $emailService, KaryawanService $cutiService)
    {
        $this->notifyService = $notifyService;
        $this->emailService = $emailService;
        $this->cutiService = $cutiService;
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::query()->findOrFail(auth()->user()->id);

        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $cutiUser = Cuti::query()->where('user_id', $user_id)
                    ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
                        return $query->where('tanggal_mulai','=',$mulai)->where('tanggal_akhir','=',$akhir);
                    })
                    ->orderBy('id', 'desc')->paginate(5)->withQueryString();

        $dataCuti = DataCuti::query()->get()->all();

        // bikin array baru dengan pengurangan izin yang sudah diambil

        if (auth()->user()->is_admin === 'admin') {
            return view('cuti.datacuti', [
                'title' => 'Tambah Permintaan Cuti Karyawan',
                'data_user' => $user,
                'data_cuti_user' => $cutiUser,
                'data_cuti' => $dataCuti
            ]);
        }

        return view('cuti.indexuser ', [
            'title' => 'Tambah Permintaan Cuti Karyawan',
            'data_user' => $user,
            'data_cuti_user' => $cutiUser,
            'data_cuti' => $dataCuti
        ]);
    }

    public function tambah(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $userId = $request['user_id'];
        $tglMulai = $request['tanggal_mulai'];
        $tglAkhir = $request['tanggal_akhir'];

        $isAvailable = $this->cutiService->isCutiIzinScheduleAvailable($userId, $tglMulai, $tglAkhir);

        if (!$isAvailable) {
            Alert::error('Peringatan!', 'Tanggal "'.$tglMulai.' sampai '.$tglAkhir.'" sudah ada ajuan Cuti/Izin');
            return redirect('/cuti');
        }

        $request->validate([
            'user_id' => 'required',
            'nama_cuti' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'alasan_cuti' => 'required',
            'foto_cuti' => 'required|image|file|max:10240',
            'status_cuti' => 'required',
        ]);

        if($request["tanggal_mulai"] === null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        }

        if($request["tanggal_akhir"] === null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        }

        // $begin = new \DateTime($request["tanggal_mulai"]);
        // $end = new \DateTime($request["tanggal_akhir"]);
        // $end = $end->modify('+1 day');

        // $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        // $daterange = new \DatePeriod($begin, $interval ,$end);

        // foreach ($daterange as $date) {
            // $request["tanggal"] = $date->format("d-m-Y");

            $request['status_cuti'] = "Pending";

            if (auth()->user()->is_admin === 'director') {
                $request['status_cuti'] = "Diterima";
            }

            $validatedData = $request->validate([
                'user_id' => 'required',
                'nama_cuti' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_akhir' => 'required',
                'alasan_cuti' => 'required',
                'foto_cuti' => 'required|image|file|max:10240',
                'status_cuti' => 'required',
            ]);

            if ($request->file('foto_cuti')) {
                $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti', 'public');
            }

            Cuti::query()->create($validatedData);

            // get the cuti data and who is the requester
            $checkUserRole = User::query()->findOrFail($request['user_id'], ['is_admin']);

            // check user is director to auto accepted
            if ($checkUserRole && $checkUserRole->is_admin === 'director') {
                // Create a new Request instance and set the necessary data
                $requestDirector = new Request([
                    'action' => 'Diterima',
                    'approval_type' => 'Diterima'
                ]);
                $requestedCutiUser = Cuti::query()->where("user_id", $request['user_id'])->first()->id;

                try {
                    $this->actionApproval($requestDirector, $requestedCutiUser);
                } catch (CustomException $e) {
                    throw new HttpException(500, $e->getMessage());
                }
            }
//         }

        $requestedUser = User::query()->findOrFail($request['user_id']);
        $users = User::query()->where('is_admin', 'admin')
            ->orWhere(function ($query) use ($requestedUser) {
                $query->where('id', function ($subQuery) use ($requestedUser) {
                    $subQuery->select('manager')
                        ->from('jabatans')
                        ->where('id', $requestedUser->jabatan_id);
                });
            })->get();

        foreach ($users as $user) {
            $type = 'Approval';
            if ($user->is_admin === 'admin') {
                $notif = 'Ada pengajuan Cuti Dari ' . auth()->user()->name;
                $url = url('/data-cuti?user_id='.$request["user_id"].'&mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]);
                $action = '/data-cuti?user_id='.$request["user_id"].'&mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"];
            } else {
                $notif = 'Ada pengajuan Cuti Dari ' . auth()->user()->name . ' Butuh Approval Anda';
                $url = url('/dashboard?user_id='.$request["user_id"].'&mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]);
                $action = '/dashboard?user_id='.$request["user_id"].'&mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"];
            }

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  $action
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);
        }

        Alert::success('Berhasil!', 'Data Berhasil di Tambahkan');
        return redirect('/cuti');
    }

    public function delete($id)
    {
        $delete = Cuti::find($id);
        // Storage::delete($delete->foto_cuti);
        $delete->delete();
        return redirect('/cuti')->with('success', 'Data Berhasil di Delete');
    }

    public function edit($id){
        if (auth()->user()->is_admin === 'admin') {
            return view('cuti.edit', [
                'title' => 'Edit Permintaan Cuti',
                'data_cuti_user' => Cuti::findOrFail($id)
            ]);
        }

        return view('cuti.edituser', [
            'title' => 'Edit Permintaan Cuti',
            'data_cuti_user' => Cuti::findOrFail($id)
        ]);

    }

    public function editProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'nama_cuti' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'alasan_cuti' => 'required',
            'foto_cuti' => 'image|file|max:10240',
        ]);

        if ($request->file('foto_cuti')) {
            // if ($request->foto_cuti_lama) {
            //     Storage::delete($request->foto_cuti_lama);
            // }
            $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti', 'public');
        }

        Cuti::where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/cuti');
    }

    public function dataCuti()
    {
        $user = User::all();
        $user_id = request()->input('user_id');
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $cuti = Cuti::when($user_id, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        })
        ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
            return $query->where('tanggal_mulai', '<=', $mulai)
                ->where('tanggal_akhir', '>=', $akhir);
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('cuti.datacuti', [
            'title' => 'Data Cuti Karyawan',
            'data_cuti' => $cuti,
            'user' => $user
        ]);
    }

//    public function tambahAdmin()
//    {
//        return view('cuti.tambahadmin', [
//            'title' => 'Tambah Data Cuti',
//            'data_user' => User::select('id', 'name')->get()
//        ]);
//    }

    public function getUserId(Request $request)
    {
        $id = $request["id"];
        $data_user = User::findOrfail($id);

        $izin_cuti = $data_user->izin_cuti;
        $izin_lainnya = $data_user->izin_lainnya;
        $izin_telat = $data_user->izin_telat;
        $izin_pulang_cepat = $data_user->izin_pulang_cepat;

        $data_cuti = array(
            [
                'nama' => 'Cuti',
                'nama_cuti' => 'Cuti ('.$izin_cuti.')'
            ],
            [
                'nama' => 'Izin Masuk',
                'nama_cuti' => 'Izin Masuk ('.$izin_lainnya.')'
            ],
            [
                'nama' => 'Izin Telat',
                'nama_cuti' => 'Izin Telat ('.$izin_telat.')'
            ],
            [
                'nama' => 'Izin Pulang Cepat',
                'nama_cuti' => 'Izin Pulang Cepat ('.$izin_pulang_cepat.')'
            ]
        );

        echo "<option value='' selected>Pilih Cuti</option>";
        foreach($data_cuti as $dc){
            echo "<option value='$dc[nama]'>$dc[nama_cuti]</option>";
        }
    }

//    public function tambahAdminProses(Request $request)
//    {
//        date_default_timezone_set('Asia/Jakarta');
//
//        if($request["tanggal_mulai"] == null) {
//            $request["tanggal_mulai"] = $request["tanggal_akhir"];
//        } else {
//            $request["tanggal_mulai"] = $request["tanggal_mulai"];
//        }
//
//        if($request["tanggal_akhir"] == null) {
//            $request["tanggal_akhir"] = $request["tanggal_mulai"];
//        } else {
//            $request["tanggal_akhir"] = $request["tanggal_akhir"];
//        }
//
//        $request['status_cuti'] = "Pending";
//        $validatedData = $request->validate([
//            'user_id' => 'required',
//            'nama_cuti' => 'required',
//            'tanggal_mulai' => 'required',
//            'tanggal_akhir' => 'required',
//            'alasan_cuti' => 'required',
//            'foto_cuti' => 'image|file|max:10240',
//            'status_cuti' => 'required',
//        ]);
//
//        if ($request->file('foto_cuti')) {
//            $validatedData['foto_cuti'] = $request->file('foto_cuti')->store('foto_cuti', 'public');
//        }
//
//        Cuti::create($validatedData);
//
//        $requestedUser = User::findOrFail($request['user_id']);
//        $users = User::where(function ($query) use ($requestedUser) {
//                $query->where('id', function ($subQuery) use ($requestedUser) {
//                    $subQuery->select('manager')
//                        ->from('jabatans')
//                        ->where('id', $requestedUser->jabatan_id);
//                });
//            })->get();
//
//        foreach ($users as $user) {
//            $type = 'Approval';
//            $notif = 'Ada pengajuan Cuti Dari ' . $requestedUser->name . ' Butuh Approval Anda';
//            $url = url('/dashboard?user_id='.$request["user_id"].'&mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]);
//            $action = '/dashboard?user_id='.$request["user_id"].'&mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"];
//
//            $user->messages = [
//                'user_id'   =>  auth()->user()->id,
//                'from'   =>  auth()->user()->name,
//                'message'   =>  $notif,
//                'action'   =>  $action
//            ];
//            $user->notify(new \App\Notifications\UserNotification);
//
//            NotifApproval::dispatch($type, $user->id, $notif, $url);
//        }
//
//        return redirect('/data-cuti')->with('success', 'Data Berhasil di Tambahkan');
//    }

    public function deleteAdmin($id)
    {
        $delete = Cuti::find($id);
        // Storage::delete($delete->foto_cuti);
        $delete->delete();
        return redirect('/data-cuti')->with('success', 'Data Berhasil di Delete');
    }

    public function editAdmin($id)
    {
        $cuti = Cuti::findOrFail($id);

        $firstMappingShift = $this->getFirstMappingShift($cuti);

        return view('cuti.editadmin', [
            'title' => 'Edit Cuti Karyawan',
            'data_cuti_karyawan' => $cuti,
            'data_shift_karyawan' => $firstMappingShift,
            'data_shift' => Shift::all()
        ]);
    }

    public function editAdminProses(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $cuti = Cuti::find($id);

        $validated = $request->validate([
            'nama_cuti' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'status_cuti' => 'required',
            'catatan' => 'nullable',
        ]);
        $cuti->update($validated);

//        $validatedMapping = $request->validate([
//            'shift_id' => 'required',
//        ]);

        $begin = new \DateTime($cuti->tanggal_mulai);
        $end = new \DateTime($cuti->tanggal_akhir);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval ,$end);

        foreach ($daterange as $tanggal) {
            Log::info("Date: " . $tanggal->format("Y-m-d"));

            $user = User::find($cuti->user_id);
            // $formatedDate = $tanggal->format("Y-m-d");
            $mapping_shift = MappingShift::where('tanggal', $tanggal)->where('user_id', $cuti->user_id)->first();

            if ($request["status_cuti"] == "Diterima") {
                if ($request["nama_cuti"] == "Cuti") {
                    $user->update([
                        'izin_cuti' => $user->izin_cuti - 1
                    ]);

                    if ($mapping_shift) {
                        if ($request["shift_id"] == $mapping_shift->shift_id) {
                            $mapping_shift->update([
                                'status_absen' => $request["nama_cuti"]
                            ]);
                        } else {
                            $mapping_shift->update([
                                'shift_id' => $request["shift_id"],
                                'status_absen' => $request["nama_cuti"]
                            ]);
                        }
                    } else {
                        MappingShift::create([
                            'user_id' => $cuti->user_id,
                            'shift_id' => $request["shift_id"],
                            'tanggal' => $tanggal,
                            'status_absen' => $request["nama_cuti"]
                        ]);
                    }
                }
                else if ($request["nama_cuti"] == "Izin Masuk") {
                    $user->update([
                        'izin_lainnya' => $user->izin_lainnya - 1
                    ]);

                    if ($mapping_shift) {
                        if ($request["shift_id"] == $mapping_shift->shift_id) {
                            $mapping_shift->update([
                                'status_absen' => $request["nama_cuti"]
                            ]);
                        } else {
                            $mapping_shift->update([
                                'shift_id' => $request["shift_id"],
                                'status_absen' => $request["nama_cuti"]
                            ]);
                        };
                    } else {
                        MappingShift::create([
                            'user_id' => $cuti->user_id,
                            'shift_id' => $request["shift_id"],
                            'tanggal' => $tanggal,
                            'status_absen' => $request["nama_cuti"]
                        ]);
                    }
                }
                else if($request["nama_cuti"] == "Izin Telat") {
                    if ($mapping_shift) {
                        $user->update([
                            'izin_telat' => $user->izin_telat - 1
                        ]);
                        if ($request["shift_id"] == $mapping_shift->shift_id) {
                            $mapping_shift->update([
                                'jam_absen' => $mapping_shift->Shift->jam_masuk,
                                'telat' => 0,
                                'lat_absen' => $user->Lokasi->lat_kantor,
                                'long_absen' => $user->Lokasi->long_kantor,
                                'jarak_masuk' => 0,
                                'foto_jam_absen' => $cuti->foto_cuti,
                                'status_absen' => $request["nama_cuti"],
                            ]);
                        } else {
                            $mapping_shift->update([
                                'shift_id' => $request["shift_id"],
                                'jam_absen' => $mapping_shift->Shift->jam_masuk,
                                'telat' => 0,
                                'lat_absen' => $user->Lokasi->lat_kantor,
                                'long_absen' => $user->Lokasi->long_kantor,
                                'jarak_masuk' => 0,
                                'foto_jam_absen' => $cuti->foto_cuti,
                                'status_absen' => $request["nama_cuti"],
                            ]);
                        }
                    } else {
                        $cuti->update(['status_cuti' => 'Pending']);
                        Alert::error('Failed', 'Anda Belum Absen Masuk Pada Tanggal Tersebut');
                        return redirect('/data-cuti');
                    }
                }
                else {
                    if ($mapping_shift) {
                        $user->update([
                            'izin_pulang_cepat' => $user->izin_pulang_cepat - 1
                        ]);

                        if ($request["shift_id"] == $mapping_shift->shift_id) {
                            $mapping_shift->update([
                                'jam_pulang' => $mapping_shift->Shift->jam_keluar,
                                'lat_pulang' => $user->Lokasi->lat_kantor,
                                'long_pulang' => $user->Lokasi->long_kantor,
                                'pulang_cepat' => 0,
                                'jarak_pulang' => 0,
                                'foto_jam_pulang' => $cuti->foto_cuti,
                                'status_absen' => $request["nama_cuti"],
                            ]);
                        } else {
                            $mapping_shift->update([
                                'shift_id' => $request["shift_id"],
                                'jam_pulang' => $mapping_shift->Shift->jam_keluar,
                                'lat_pulang' => $user->Lokasi->lat_kantor,
                                'long_pulang' => $user->Lokasi->long_kantor,
                                'pulang_cepat' => 0,
                                'jarak_pulang' => 0,
                                'foto_jam_pulang' => $cuti->foto_cuti,
                                'status_absen' => $request["nama_cuti"],
                            ]);
                        };
                    } else {
                        $cuti->update(['status_cuti' => 'Pending']);
                        Alert::error('Failed', 'Anda Belum Absen Masuk Pada Tanggal Tersebut');
                        return redirect('/data-cuti');
                    }
                }
//
//                $user = User::find($cuti->user_id);
//                $type = 'Approved';
//                $notif = 'Cuti Anda Telah Diterima Oleh ' . auth()->user()->name;
//                $url = url('/cuti?mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]);
//
//                $user->messages = [
//                    'user_id'   =>  auth()->user()->id,
//                    'from'   =>  auth()->user()->name,
//                    'message'   =>  $notif,
//                    'action'   =>  '/cuti?mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]
//                ];
//                $user->notify(new \App\Notifications\UserNotification);
//
//                NotifApproval::dispatch($type, $user->id, $notif, $url);
            }
            else if ($request["status_cuti"] == "Ditolak") {
//                $user = User::find($cuti->user_id);
//                $type = 'Rejected';
//                $notif = 'Cuti Anda Telah Ditolak Oleh ' . auth()->user()->name;
//                $url = url('/cuti?mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]);
//
//                $user->messages = [
//                    'user_id'   =>  auth()->user()->id,
//                    'from'   =>  auth()->user()->name,
//                    'message'   =>  $notif,
//                    'action'   =>  '/data-cuti?mulai='.$request["tanggal_mulai"].'&akhir='.$request["tanggal_akhir"]
//                ];
//                $user->notify(new \App\Notifications\UserNotification);
//
//                NotifApproval::dispatch($type, $user->id, $notif, $url);
            }
            else {
                if ($mapping_shift){
                    $mapping_shift->update(['shift_id' => $request['shift_id']]);
                }
            }
        }

        $this->notifApproval($cuti, $request["approval_action"]);

        if (auth()->user()->is_admin != 'admin') {
            return redirect()->route('dashboard', [
                'user_id' => $cuti->user_id,
                'mulai' => $cuti->tanggal_mulai,
                'akhir' => $cuti->tanggal_akhir
            ]);
        }
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/data-cuti');
    }

    public function downloadSurat($id) {
        $dataCuti = Cuti::find($id);

        $tanggalMulai = new \DateTime($dataCuti->tanggal_mulai);
        $tanggalAkhir = new \DateTime($dataCuti->tanggal_akhir);
        $interval = $tanggalMulai->diff($tanggalAkhir);

        $days = $interval->days + 1;

        // set data for surat cuti
        $settings = settings::first();
        $logo_path = storage_path('app/public/' . $settings->logo);
        $default_logo_path = public_path('assets/img/neoma_logo.jpg');
        $img_null_path = public_path('assets/img/No-image-available.png');
        $pending_stample_path = public_path('assets/img/pending_stample.png');
        $denied_stample_path = public_path('assets/img/rejected_stample.png');

        // retrieve logo data
        $logo = $this->getLogoData($logo_path, $default_logo_path);

        // retrieve the requested data surat cuti
        $dataSurat = $this->dataSuratCuti($dataCuti, $img_null_path, $pending_stample_path, $denied_stample_path);

        $pdf = Pdf::loadView('cuti.suratCutiIzin', [
            'title' => 'Surat Cuti/Izin',
            'data' => $dataCuti,
            'interval' => $days,
            'settings' => $settings,
            'logo' => $logo,
            'dataSurat' => $dataSurat
        ]);

        return $pdf->stream();
    }

    public function previewSurat($id) {
        $dataCuti = Cuti::find($id);

        $tanggalMulai = new \DateTime($dataCuti->tanggal_mulai);
        $tanggalAkhir = new \DateTime($dataCuti->tanggal_akhir);
        $interval = $tanggalMulai->diff($tanggalAkhir);

        $days = $interval->days + 1;

        // set data for surat cuti
        $settings = settings::first();
        $logo_path = storage_path('app/public/' . $settings->logo);
        $default_logo_path = public_path('assets/img/neoma_logo.jpg');
        $img_null_path = public_path('assets/img/No-image-available.png');
        $pending_stample_path = public_path('assets/img/pending_stample.png');
        $denied_stample_path = public_path('assets/img/rejected_stample.png');

        // retrieve logo data
        $logo = $this->getLogoData($logo_path, $default_logo_path);

        // retrieve the requested data surat cuti
        $dataSurat = $this->dataSuratCuti($dataCuti, $img_null_path, $pending_stample_path, $denied_stample_path);

        return view('cuti.suratCutiIzin', [
            'title' => 'Surat Cuti/Izin',
            'data' => $dataCuti,
            'interval' => $days,
            'settings' => $settings,
            'logo' => $logo,
            'dataSurat' => $dataSurat
        ]);
    }

    private function dataSuratCuti($dataCuti, $img_null_path, $pending_stample_path, $rejected_stample_path) {
        // initiate needed variable
        $dataResult = [];
        $approval1 = null;
        $approval2 = null;

        // check the dataCuti belonging to whom (default allowed is user, head, manager)
        if ($dataCuti->User->is_admin != null) {
            try {
                // check approval value based requested user
                if ($dataCuti->User->is_admin == 'director') {
                    // fetch director data itself
                    $hrchy1 = $dataCuti->User;
                    $approval1 = $dataCuti->approval3;
                } else {
                    // fetch the head based on the requested user's jabatan_id
                    $hrchy1Jabatan = Jabatan::findOrFail($dataCuti->User->jabatan_id);
                    $hrchy1 = User::findOrFail($hrchy1Jabatan->manager);
                }
                if ($dataCuti->User->is_admin == 'user') {
                    $approval1 = $dataCuti->approval1;
                } elseif ($dataCuti->User->is_admin == 'head') {
                    $approval1 = $dataCuti->approval2;
                } elseif ($dataCuti->User->is_admin == 'manager') {
                    $approval1 = $dataCuti->approval3;
                }

                // Retrieve the approval data
                $hrchy1Data = $this->getApprovalData(
                    $approval1, $hrchy1, $img_null_path,
                    $rejected_stample_path, $pending_stample_path
                );

                // insert the data to result variable
                $dataResult['hrchy1'] = $hrchy1Data;
            } catch (\Exception $e) {
                throw new CustomException('An error occurred during retrieve hierarchy 1 data',
                    $e instanceof HttpException ? $e->getStatusCode() : 500, $e, ['additional' => 'data']);
            }

            //check if requested user is not manager (default allowed is user, head)
            if ($dataCuti->User->is_admin != 'manager' && $dataCuti->User->is_admin != 'director') {
                try {
                    // Fetch the manager based on the head's jabatan manager column
                    $hrchy2Jabatan = Jabatan::findOrFail($hrchy1->jabatan_id);
                    $hrchy2 = User::findOrFail($hrchy2Jabatan->manager);

                    //check approval value based requested user
                    if ($dataCuti->User->is_admin == 'user') {
                        $approval2 = $dataCuti->approval2;
                    } elseif ($dataCuti->User->is_admin == 'head') {
                        $approval2 = $dataCuti->approval3;
                    }

                    // Retrieve the approval data
                    $hrchy2Data = $this->getApprovalData(
                        $approval2, $hrchy2, $img_null_path,
                        $rejected_stample_path, $pending_stample_path
                    );

                    // insert the data to result variable
                    $dataResult['hrchy2'] = $hrchy2Data;
                } catch (\Exception $e) {
                    throw new CustomException('An error occurred during retrieve hierarchy 2 data',
                        $e instanceof HttpException ? $e->getStatusCode() : 500, $e, ['additional' => 'data']);
                }

                //check if requested user is not head (default allowed is user)
                if ($dataCuti->User->is_admin != 'head') {
                    try {
                        // Fetch the director based on the manager's jabatan manager column
                        $hrchy3Jabatan = Jabatan::findOrFail($hrchy2->jabatan_id);
                        $hrchy3 = User::findOrFail($hrchy3Jabatan->manager);

                        // Retrieve the approval data
                        $hrchy3Data = $this->getApprovalData(
                            $dataCuti->approval3, $hrchy3, $img_null_path,
                            $rejected_stample_path, $pending_stample_path
                        );

                        // insert the data to result variable
                        $dataResult['hrchy3'] = $hrchy3Data;
                    } catch (\Exception $e) {
                        throw new CustomException('An error occurred during retrieve hierarchy 3 data',
                            $e instanceof HttpException ? $e->getStatusCode() : 500, $e, ['additional' => 'data']);
                    }
                }
            }
        }

        return $dataResult;
    }

    private function getLogoData($logo_path, $default_logo_path) {
        // check logo existed in storage
        // if not using default logo in public/assets/img
        if (is_file($logo_path)) {
            return [
                'mime' => mime_content_type($logo_path),
                'data' => base64_encode(file_get_contents($logo_path))
            ];
        } else {
            return [
                'mime' => mime_content_type($default_logo_path),
                'data' => base64_encode(file_get_contents($default_logo_path))
            ];
        }
    }

    private function getApprovalData($approval, $user, $img_null_path, $rejected_stample_path, $pending_stample_path) {
        // check if passed approval is not null (head/manager/director)
        // if null set data ttd to pending
        if ($approval != null) {
            // check if approval is accepted
            //if not accepted set data ttd to rejected
            if ($approval == 'Diterima') {
                $ttd_path = storage_path('app/public/' . $user->ttd_karyawan);

                //check if ttd file exist in storage
                // if not exist using img null in public/assets/img
                if (is_file($ttd_path)) {
                    return [
                        'name' => $user->name,
                        'nik' => $user->nik,
                        'jabatan_id' => $user->jabatan->nama_jabatan,
                        'ttd_mime' => mime_content_type($ttd_path),
                        'ttd_data' => base64_encode(file_get_contents($ttd_path))
                    ];
                } else {
                    return [
                        'name' => $user->name,
                        'nik' => $user->nik,
                        'jabatan_id' => $user->jabatan->nama_jabatan,
                        'ttd_mime' => mime_content_type($img_null_path),
                        'ttd_data' => base64_encode(file_get_contents($img_null_path))
                    ];
                }
            } else {
                return [
                    'name' => $user->name,
                    'nik' => $user->nik,
                    'jabatan_id' => $user->jabatan->nama_jabatan,
                    'ttd_mime' => mime_content_type($rejected_stample_path),
                    'ttd_data' => base64_encode(file_get_contents($rejected_stample_path))
                ];
            }
        } else {
            return [
                'name' => $user->name,
                'nik' => $user->nik,
                'jabatan_id' => $user->jabatan->nama_jabatan,
                'ttd_mime' => mime_content_type($pending_stample_path),
                'ttd_data' => base64_encode(file_get_contents($pending_stample_path))
            ];
        }
    }

    public function actionApproval(Request $request, $id) {
        $cuti = Cuti::findOrFail($id);
        $FirstMapping = $this->getFirstMappingShift($cuti);

        $approvalAction = $request->input('action');
        $approvalType = $request->input('approval_type');
        $cutiUserRole = $cuti->User->is_admin;

        if ($approvalAction == 'Diterima') {
            try {
                // check approval submission from director, head, manager, then user
                if ($cutiUserRole == 'director') {
                    // update approval value based on flow for director
                    $cuti->update([
                        'approval1' => $approvalAction,
                        'approval2' => $approvalAction,
                        'approval3' => $approvalAction
                    ]);
                }
                elseif ($cutiUserRole == 'manager') {
                    if ($cuti->approval1 == null && $cuti->approval2 == null) {
                        // update approval value based on flow; default: director
                        $cuti->update([
                            'approval1' => $approvalAction,
                            'approval2' => $approvalAction,
                            $approvalType => $approvalAction
                        ]);
                    }
                }
                elseif ($cutiUserRole == 'head') {
                    // update approval value based on flow; default: manager->director
                    if ($cuti->approval1 == null) {
                        $cuti->update([
                            'approval1' => $approvalAction,
                            $approvalType => $approvalAction
                        ]);
                    } else {
                        $cuti->update([
                            $approvalType => $approvalAction
                        ]);
                    }
                }
                else {
                    // update approval value based on flow; default: head->manager->director
                //    $cuti->update([$approvalType => $approvalAction]);
                }

                // check auth user is director to finish the approval
                if (auth()->user()->is_admin == 'director' || $cuti->User->is_admin == 'director') {
                    // Create a new Request instance and set the necessary data
                    $request = new Request([
                        'status_cuti' => $approvalAction,
                        'nama_cuti' => $cuti->nama_cuti,
                        'tanggal_mulai' => $cuti->tanggal_mulai,
                        'tanggal_akhir' => $cuti->tanggal_akhir,
                        'shift_id' => $FirstMapping->shift_id ?? null,
                        'approval_action' => $approvalAction
                    ]);

                    // Call update data cuti and mapping shift function with the manually created Request and $id
                    $this->editAdminProses($request, $id);
                }
                else {
                    // Call approval notification function
                    $this->notifApproval($cuti, $approvalAction);
                }
            }
            catch (\Exception $e) {
                throw new CustomException('An error occurred during Approval accept action', $e instanceof HttpException ? $e->getStatusCode() : 500, $e, ['additional' => 'data']);
            }
        }
        if ($approvalAction == 'Ditolak') {
            try {
                $request = new Request([
                    'status_cuti' => $approvalAction,
                    'nama_cuti' => $cuti->nama_cuti,
                    'tanggal_mulai' => $cuti->tanggal_mulai,
                    'tanggal_akhir' => $cuti->tanggal_akhir,
                    'shift_id' => $FirstMapping->shift_id ?? null,
                    'approval_action' => $approvalAction
                ]);

                // check approval submission from head, manager, then user
                if ($cutiUserRole == 'manager') {
                    if ($cuti->approval1 == null && $cuti->approval2 == null) {
                        // update approval value based on flow; default: director
                        $cuti->update([
                            'approval1' => $approvalAction,
                            'approval2' => $approvalAction,
                            $approvalType => $approvalAction
                        ]);
                    }
                }
                elseif ($cutiUserRole == 'head') {
                    // update approval value based on flow; default: manager->director
                    if ($cuti->approval1 == null) {
                        $cuti->update([
                            'approval1' => $approvalAction,
                            $approvalType => $approvalAction,
                            'approval3' => $approvalAction,
                        ]);
                    } else {
                        $cuti->update([
                            $approvalType => $approvalAction
                        ]);
                    }
                }
                else {
                    // check approval value user; default : head->manager->director
                    if ($cuti->approval1 != null) {
                        if ($cuti->approval2 != null) {
                            // if cuti denied from director goes here
                            $cuti->update([
                                'approval3' => $approvalAction
                            ]);
                        } else {
                            // if cuti denied from manager goes here
                            $cuti->update([
                                'approval2' => $approvalAction,
                                'approval3' => $approvalAction,
                            ]);
                        }
                    }
                    else {
                        // if cuti denied from head goes here
                        $cuti->update([
                            'approval1' => $approvalAction,
                            'approval2' => $approvalAction,
                            'approval3' => $approvalAction,
                        ]);
                    }
                }

                $this->editAdminProses($request, $id);
            }
            catch (\Exception $e) {
                throw new CustomException('An error occurred during Approval denied action', $e instanceof HttpException ? $e->getStatusCode() : 500, $e, ['additional' => 'data']);
            }

        }

        // Define the mapping of session keys to $cuti properties
        $cutiData = [
            'cuti_user_id' => 'user_id',
            'cuti_mulai' => 'tanggal_mulai',
            'cuti_akhir' => 'tanggal_akhir',
        ];

        // Store the values in the session using a foreach loop
        foreach ($cutiData as $sessionKey => $cutiProperty) {
            session([$sessionKey => $cuti->$cutiProperty]);
        }

        return redirect()->route('dashboard');
    }

    public function getFirstMappingShift($cuti) {
        $mappingShift = MappingShift::where('user_id', $cuti->user_id)
            ->whereBetween('tanggal', [$cuti->tanggal_mulai, $cuti->tanggal_akhir])->get();

        $shiftNames = $mappingShift->pluck('Shift.nama_shift')->unique();

        if ($shiftNames->count() > 1) {
            Alert::error('Error', 'Shift pada rentang cuti tidak sama. Buat baru pengajuan atau edit mapping shift karyawan');
            return redirect()->back();
        }

        return $mappingShift->first();
    }

    public function notifApproval($cuti, $approvalAction) {
        if ($approvalAction == 'Diterima') {
            $type = 'Approved';
        } else {
            $type = 'Rejected';
        }

        // Fetch user sender data
        $userQuery = User::query();
        $userSender = (clone $userQuery)->select("id", "name")
            ->where("id", auth()->user()->id)->first();

        // Fetch users to notify (admin, head, manager, director) based on requested cuti
        $requestedUser = auth()->user()->jabatan_id;
        $users = (clone $userQuery)->where('is_admin', 'admin')
            ->orWhere('id', $cuti->user_id)
            ->orWhere(function ($query) use ($approvalAction, $requestedUser) {
                $query->when($approvalAction == 'Diterima', function ($query) use ($requestedUser) {
                    $query->where('id', function ($subQuery) use ($requestedUser) {
                        $subQuery->select('manager')
                            ->from('jabatans')
                            ->where('id', $requestedUser);
                    });
                });
            })->get();


        $scenario = [];

        foreach ($users as $user) {
            // check if requested user to notif is admin
            if ($user->is_admin == 'admin') {
                // check if requested user to notif is from director
                if (auth()->user()->is_admin == 'director' && $approvalAction == 'Diterima') {
                    $notif = 'Pengajuan Cuti Dari ' . $cuti->User->name . ' ' . $approvalAction .
                        ' oleh ' . auth()->user()->name . ' (' . auth()->user()->Jabatan->nama_jabatan . '). Segera edit mapping shift karyawan';
                    $url = url('/pegawai/shift'.$cuti->user_id.'?mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir);
//                    $action = '/pegawai/shift/'.$cuti->user_id.'?mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir;
                }
                else {
                    $notif = 'Pengajuan Cuti Dari ' . $cuti->User->name . ' ' . $approvalAction .
                        ' oleh ' . auth()->user()->name . ' (' . auth()->user()->Jabatan->nama_jabatan . ')';
                    $url = url('/data-cuti?user_id='.$cuti->user_id.'&mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir);
//                    $action = '/data-cuti?user_id='.$cuti->user_id.'&mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir;
                }

            }
            // check if requested accepted cuti user to notif is not user director and admin, also cuti is from head or manager
            if ((($user->is_admin == 'head' && $cuti->User->is_admin != 'head') ||
                    ($user->is_admin == 'manager' && $cuti->User->is_admin != 'manager') || $user->is_admin == 'director') &&
                $cuti->approval3 == null && $approvalAction == 'Diterima')
            {
                $notif = 'Ada pengajuan Cuti Dari ' . $cuti->User->name . ' Butuh Approval Anda';
                $url = url('/dashboard?cuti_user_id=' . $cuti->user_id . '&cuti_mulai=' . $cuti->tanggal_mulai . '&cuti_akhir=' . $cuti->tanggal_akhir);
//                $action = '/dashboard?user_id='.$cuti->user_id.'&mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir;
            }
            // check if requested user to notif is user and also cuti from head and manager
            if (($cuti->User->is_admin == 'user' && $user->is_admin == 'user') ||
                ($cuti->User->is_admin == 'head' && $user->is_admin == 'head') ||
                ($cuti->User->is_admin == 'manager' && $user->is_admin == 'manager'))
            {
                $notif = 'Pengajuan Cuti Anda ' . $cuti->User->name . ', ' . $approvalAction .
                    ' oleh ' . auth()->user()->name . ' (' . auth()->user()->Jabatan->nama_jabatan . ')';
                $url = url('/cuti?mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir);
//                $action = '/cuti?mulai='.$cuti->tanggal_mulai.'&akhir='.$cuti->tanggal_akhir;
            }

            // Determine greeting based on executionTime
            $executionTime = now()->format('H:i:s'); // Format the current time for logging
            // Insert each scenario into the array
            $scenario[$user->id] = [
                'type' => $type,
                'dataType' => 'single object',
                'sender' => $userSender,
                'receivers' => $user,
                'messageTemplate' => $notif,
                'url' => $url,
                'executionTime' => $executionTime
            ];

//            try {
//                // Send Notify to targeted user
//                $this->notifyService->sendNotifies($users ,[
//                    'type' => $type,
//                    'sender' => $userSender,
//                    'receivers' => $user,
//                    'messageTemplate' => $notif,
//                    'url' => $url,
//                    'executionTime' => $executionTime
//                ]);
//            } catch (\Exception $e) {
//                Log::error('Failed to send notify: ' . $e->getMessage());
//                Alert::error('Error', 'An error occurred, while sending notify. Please try again later.');
//            }
//            $user->messages = [
//                'user_id'   =>  auth()->user()->id,
//                'from'   =>  auth()->user()->name,
//                'message'   =>  $notif,
//                'action'   =>  $action
//            ];
//
//            $user->notify(new \App\Notifications\UserNotification);
//
//            NotifApproval::dispatch($type, $user->id, $notif, $url);
//
//            Mail::to($user->email)->send(new ApprovalCutiMail($greeting, $user, $notif, $cuti, $url));
        }

        try {
            // Send Notify to targeted user
            $this->notifyService->sendNotifies($scenario);
        } catch (\Exception $e) {
            Log::error('Failed to send notify: ' . $e->getMessage());
            Alert::error('Error', 'An error occurred, while sending notify. Please try again later.');
        }

        try {
            // Send email
            $this->emailService->sendEmails($scenario, function($greeting, $user, $notif, $url) {
                Mail::to($user->email)->send(new ApprovalCutiMail( $greeting, $user, $notif, $url));
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            Alert::error('Error', 'An error occurred, while sending email. Please try again later.');
        }
    }

//    public function previewSuratAsImage($id) {
//        $dataCuti = Cuti::find($id);
//
//        $tanggalMulai = new \DateTime($dataCuti->tanggal_mulai);
//        $tanggalAkhir = new \DateTime($dataCuti->tanggal_akhir);
//        $interval = $tanggalMulai->diff($tanggalAkhir);
//
//        $days = $interval->days + 1;
//
//        $pdf = Pdf::loadView('cuti.suratCutiIzin', [
//            'title' => 'Surat Cuti/Izin',
//            'data' => $dataCuti,
//            'interval' => $days,
//        ]);
//
//        $pdfPath = storage_path('app/public/suratCuti.pdf');
//        $pdf->save($pdfPath);
//
//        // Save the PDF path to the database
//        $dataCuti->surat_cuti = $pdfPath;
//        $dataCuti->save();
//
//        $imagick = new Imagick();
//        $imagick->setResolution(300, 300);
//        $imagick->readImage($pdfPath);
//        $imagick->setImageFormat('jpeg');
//        $imagick->setImageCompressionQuality(90);
//
//        $imagePath = storage_path('app/public/suratCuti.jpg');
//        $imagick->writeImage($imagePath);
//
//        return response()->file($imagePath, [
//            'Content-Type' => 'image/jpeg',
//        ]);
//    }
}
