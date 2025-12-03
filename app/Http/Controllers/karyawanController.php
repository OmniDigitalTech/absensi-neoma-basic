<?php

namespace App\Http\Controllers;


use App\Mail\PendingAbsensiMail;
use App\Models\Cuti;
use App\Models\DataCuti;
use App\Models\Deduksi;
use App\Models\dinasLuar;
use App\Models\DynamicUpah;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Kesehatan;
use App\Models\Ketenagakerjaan;
use App\Models\KetenagakerjaanJkk;
use App\Models\Lembur;
use App\Models\Lokasi;
use App\Models\settings;
use App\Models\Upah;
use App\Models\User;
use App\Models\MappingShift;
use App\Models\ResetCuti;
use App\Models\Shift;
use App\Models\Sip;
use App\Services\EmailService;
use App\Services\KaryawanService;
use App\Services\NotifyService;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Imports\UsersImport;
use App\Models\Payroll;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;


class karyawanController extends Controller
{
    protected $notifyService;
    protected $emailService;
    protected $karyawanService;

    public function __construct(NotifyService $notifyService, EmailService $emailService, KaryawanService $karyawanService)
    {
        $this->notifyService = $notifyService;
        $this->emailService = $emailService;
        $this->karyawanService = $karyawanService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $data = User::query()->when($search, function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%'.$search.'%')
                          ->orWhere('email', 'LIKE', '%'.$search.'%')
                          ->orWhere('telepon', 'LIKE', '%'.$search.'%')
                          ->orWhere('username', 'LIKE', '%'.$search.'%')
                          ->orWhereHas('Jabatan', function ($query) use ($search) {
                              $query->where('nama_jabatan', 'LIKE', '%'.$search.'%');
                          });
                })
                ->orderBy('name', 'ASC')
                ->paginate(10000)
                ->withQueryString();


        if (auth()->user()->is_admin === 'admin') {
            return view('karyawan.index', [
                'title' => 'Pegawai',
                'data_user' => $data
            ]);
        }

        return view('karyawan.indexUser', [
            'title' => 'Pegawai',
            'data_user' => $data
        ]);
    }

    public function euforia()
    {
        date_default_timezone_set('Asia/Jakarta');

        $data = User::query()->where('tgl_lahir', date('Y-m-d'))
                ->orderBy('name', 'ASC')
                ->paginate(10000)
                ->withQueryString();

        return view('karyawan.euforia', [
            'title' => 'Euforia',
            'data_user' => $data
        ]);
    }

    public function show($id)
    {
        $user = User::query()->find($id);

        return view('karyawan.show', [
            'title' => 'Detail Karyawan',
            'user' => $user
        ]);
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx,csv|max:5000'
        ]);
        if ($request->file('file_excel')) {
            $nama_file = $request->file('file_excel')->store('file_excel');

        }

        Excel::import(new UsersImport, public_path('/storage/'.$nama_file));
        return back()->with('success', 'Data Berhasil Di Import');
    }

    /**
     * @throws \Throwable
     */
    private function getKaryawanBenefits($golonganId, $tipeKaryawan) {
        $karyawanBenefits = $this->karyawanService->getCutiIzinUpahDeduksiKaryawan($golonganId, $tipeKaryawan);
        $cutiIzin = collect($karyawanBenefits['cuti_izin']);
        $upah = collect($karyawanBenefits['upah']);
//        $dynamicUpah = collect($karyawanBenefits['dynamicUpah']);
        $deduksi = collect($karyawanBenefits['deduksi']);
        $bpjsKesehatan = collect($karyawanBenefits['bpjsKesehatan']);
        $bpjsKetenagakerjaan = collect($karyawanBenefits['bpjsKetenagakerjaan']);
        $bpjsKetenagakerjaanJkk = collect($karyawanBenefits['bpjsKetenagakerjaanJkk']);

        return response()->json([
            'cuti_izin_view' => view('karyawan.partials.cuti_izin_tambah_karyawan', [ 'data_cuti_izin' => $cutiIzin ])->render(),
            'upah_view' => view('karyawan.partials.upah_tambah_karyawan', [ 'data_upah' => $upah ])->render(),
//            'dynamic_upah_view' => view('karyawan.partials.dynamic_upah_tambah_karyawan', [ 'data_dynamic_upah' => $dynamicUpah ])->render(),
            'deduksi_view' => view('karyawan.partials.deduksi_tambah_karyawan',
                [
                    'data_deduksi' => $deduksi, 'tipe_karyawan' => $tipeKaryawan,
                    'data_bpjs_kesehatan' => $bpjsKesehatan,
                    'data_bpjs_ketenagakerjaan' => $bpjsKetenagakerjaan,
                    'data_bpjs_ketenagakerjaan_jkk' => $bpjsKetenagakerjaanJkk
                ])->render()
        ]);
    }

    public function tambahKaryawan(Request $request)
    {
        if ($request->ajax()) {
            $golonganId = $request->input('golongan_id');
            $tipeKaryawan = $request->input('tipe_karyawan');
            return $this->getKaryawanBenefits($golonganId, $tipeKaryawan);
        }

        return view('karyawan.tambah', [
            "title" => 'Tambah Pegawai',
            "data_jabatan" => Jabatan::query()->select('id', 'nama_jabatan')->get(),
            "data_golongan" => Golongan::query()->select('id', 'name')->get(),
            "data_lokasi" => Lokasi::query()->where('status', 'approved')->get()
        ]);
    }

    public function tambahKaryawanProses(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'nik' => 'required|max:255',
            'tgl_lahir' => 'required',
            'email' => 'required|email:dns|unique:users',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|max:255',
            'telepon' => 'required',
            'rekening' => 'nullable',
            'tgl_join' => 'required',
            'lokasi_id' => 'required',
            'gender' => 'required',
            'status_nikah' => 'required',
            'is_admin' => 'required',
            'tipe_karyawan'=> 'required',
            'jabatan_id' => 'required',
            'golongan_id' => 'required',
            'alamat' => 'required',
            'foto_karyawan' => 'image|file|max:10240',
            'ttd_karyawan' => 'image|file|max:10240',
        ]);

        if ($request->file('foto_karyawan')) {
            $validatedData['foto_karyawan'] = $request->file('foto_karyawan')->store('foto_karyawan');
        }

        if ($request->file('ttd_karyawan')) {
            $validatedData['ttd_karyawan'] = $request->file('ttd_karyawan')->store('ttd_karyawan');
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
//        Log::info(json_encode($validatedData, JSON_THROW_ON_ERROR));
        User::query()->create($validatedData);
        return redirect('/pegawai')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function detail(Request $request, $id)
    {
        if ($request->ajax() && $request->input('golongan_id') && $request->input('tipe_karyawan')) {
//            Log::info('cek ajax');
            $changedGolonganId = $request->input('golongan_id');
            $changedTipeKaryawan = $request->input('tipe_karyawan');

            return $this->getKaryawanBenefits($changedGolonganId, $changedTipeKaryawan);
        }

        $golonganId = User::query()->find($id)->golongan_id;
        $tipeKaryawan = User::query()->find($id)->tipe_karyawan;

        $karyawanBenefits = $this->karyawanService->getCutiIzinUpahDeduksiKaryawan($golonganId, $tipeKaryawan);
        $cutiIzin = $karyawanBenefits['cuti_izin'];
        $upah = $karyawanBenefits['upah'];
//            $dynamicUpah = $karyawanBenefits['dynamicUpah'];
        $deduksi = $karyawanBenefits['deduksi'];
        $bpjsKesehatan = $karyawanBenefits['bpjsKesehatan'];
        $bpjsKetenagakerjaan = $karyawanBenefits['bpjsKetenagakerjaan'];
        $bpjsKetenagakerjaanJkk = $karyawanBenefits['bpjsKetenagakerjaanJkk'];

        return view('karyawan.editkaryawan', [
            'title' => 'Detail Pegawai',
            'karyawan' => User::query()->find($id),
            'data_jabatan' => Jabatan::all(),
            'data_golongan' => Golongan::all(),
            'data_lokasi' => Lokasi::query()->where('status', 'approved')->get(),
            'data_cuti_izin' => $cutiIzin,
            'data_upah' =>  $upah,
//            'data_dynamic_upah' =>  $dynamicUpah,
            'data_deduksi' => $deduksi,
            'data_bpjs_kesehatan' => $bpjsKesehatan,
            'data_bpjs_ketenagakerjaan' => $bpjsKetenagakerjaan,
            'data_bpjs_ketenagakerjaan_jkk' => $bpjsKetenagakerjaanJkk,
        ]);
    }

    /**
     * @throws FileNotFoundException
     */
    public function editKaryawanProses(Request $request, $id)
    {
//        if($request["izin_cuti"] === null) {
//            $request["izin_cuti"] = "0";
//        } else {
//            $request["izin_cuti"];
//        }
//
//        if($request["izin_lainnya"] == null) {
//            $request["izin_lainnya"] = "0";
//        }  else {
//            $request["izin_lainnya"];
//        }
//
//        if($request["izin_telat"] == null) {
//            $request["izin_telat"] = "0";
//        }  else {
//            $request["izin_telat"];
//        }
//
//        if($request["izin_pulang_cepat"] == null) {
//            $request["izin_pulang_cepat"] = "0";
//        }  else {
//            $request["izin_pulang_cepat"];
//        }

        $rules = [
//            'name' => 'required|max:255',
//            'foto_karyawan' => 'image|file|max:10240',
//            'ttd_karyawan' => 'image|file|max:10240',
//            'telepon' => 'required',
//            'password' => 'required',
//            'tgl_lahir' => 'required',
//            'gender' => 'required',
//            'tgl_join' => 'required',
//            'status_nikah' => 'required',
//            'alamat' => 'required',
//            'is_admin' => 'required',
//            'jabatan_id' => 'required',
//            'lokasi_id' => 'required',
//            'rekening' => 'nullable',

            'name' => 'required|max:255',
            'nik' => 'required|max:255',
            'tgl_lahir' => 'required',
            'password' => 'required|min:6|max:255',
            'telepon' => 'required',
            'rekening' => 'nullable',
            'tgl_join' => 'required',
            'lokasi_id' => 'required',
            'gender' => 'required',
            'status_nikah' => 'required',
            'is_admin' => 'required',
            'tipe_karyawan'=> 'required',
            'jabatan_id' => 'required',
            'golongan_id' => 'required',
            'alamat' => 'required',
            'foto_karyawan' => 'image|file|max:10240',
            'ttd_karyawan' => 'image|file|max:10240',
//            'izin_cuti' => 'required',
//            'izin_lainnya' => 'required',
//            'izin_telat' => 'required',
//            'izin_pulang_cepat' => 'required',
//            'gaji_pokok' => 'required',
//            'makan_transport' => 'required',
//            'lembur' => 'required',
//            'kehadiran' => 'required',
//            'thr' => 'required',
//            'bonus' => 'required',
//            'izin' => 'required',
//            'terlambat' => 'required',
//            'mangkir' => 'required',
//            'saldo_kasbon' => 'required',
        ];


        $userId = User::query()->find($id);

        if ($request->email !== $userId?->email) {
            $rules['email'] = 'required|email:dns|unique:users';
        }

        if ($request->username !== $userId?->username) {
            $rules['username'] = 'required|max:255|unique:users';
        }

        $validatedData = $request->validate($rules);
//        $validatedData['gaji_pokok'] = str_replace(',', '', $validatedData['gaji_pokok']);
//        $validatedData['makan_transport'] = str_replace(',', '', $validatedData['makan_transport']);
//        $validatedData['lembur'] = str_replace(',', '', $validatedData['lembur']);
//        $validatedData['kehadiran'] = str_replace(',', '', $validatedData['kehadiran']);
//        $validatedData['thr'] = str_replace(',', '', $validatedData['thr']);
//        $validatedData['bonus'] = str_replace(',', '', $validatedData['bonus']);
//        $validatedData['izin'] = str_replace(',', '', $validatedData['izin']);
//        $validatedData['terlambat'] = str_replace(',', '', $validatedData['terlambat']);
//        $validatedData['mangkir'] = str_replace(',', '', $validatedData['mangkir']);
//        $validatedData['saldo_kasbon'] = str_replace(',', '', $validatedData['saldo_kasbon']);

        if ($request->file('foto_karyawan')) {
            if ($request->foto_karyawan_lama) {
                Storage::delete($request->foto_karyawan_lama);
            }
            $validatedData['foto_karyawan'] = $request->file('foto_karyawan')->store('foto_karyawan');
        }

        if ($request->file('ttd_karyawan')) {
            if ($request->ttd_karyawan_lama) {
                Storage::delete($request->ttd_karyawan_lama);
            }
            $validatedData['ttd_karyawan'] = $request->file('ttd_karyawan')->store('ttd_karyawan');
        }

        $path = public_path('neural.json');
        $neural = File::get($path);
        $dataface = json_decode($neural, true);

        foreach ($dataface as &$item) {
            if ($item['label'] === $userId?->username) {
                $item['label'] = $request->username;
            }
        }
        File::put($path, json_encode($dataface, JSON_PRETTY_PRINT));

        User::query()->where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/pegawai');
    }

    /**
     * @throws FileNotFoundException
     */
    public function deleteKaryawan($id)
    {
        $delete = User::query()->find($id);
        MappingShift::query()->where('user_id', $id)->delete();
        Lembur::query()->where('user_id', $id)->delete();
        Cuti::query()->where('user_id', $id)->delete();
        Sip::query()->where('user_id', $id)->delete();
        Payroll::query()->where('user_id', $id)->delete();
        Storage::delete($delete->foto_karyawan);
        Storage::delete($delete->ttd_karyawan);
        $path = public_path('neural.json');
        $neural = File::get($path);
        $dataface = json_decode($neural, true);

        $filterface = array_filter($dataface, static function($item) use ($delete) {
            return $item['label'] !== $delete->username;
        });
        File::put($path, json_encode(array_values($filterface), JSON_PRETTY_PRINT));
        $delete?->delete();
        return redirect('/pegawai')->with('success', 'Data Berhasil di Delete');
    }

    public function editpassword($id)
    {
        return view('karyawan.editpassword', [
            'title' => 'Edit Password',
            'karyawan' => User::query()->find($id)
        ]);
    }

    public function face($id)
    {
        return view('karyawan.face', [
            'title' => 'Daftar Wajah',
            'karyawan' => User::query()->find($id)
        ]);
    }

    /**
     * @throws FileNotFoundException
     */
    public function ajaxDescrip(Request $request)
    {
        $path = public_path('neural.json');
        $neural = File::get($path);
        $dataface = json_decode($neural, true);
        $user = User::find($request->user_id);

        $filterface = array_filter($dataface, function($item) use ($user) {
            return $item['label'] !== $user->username;
        });

        File::put($path, json_encode(array_values($filterface), JSON_PRETTY_PRINT));

        $json = file_get_contents('neural.json');
        if(strlen($json) > 4){
            $string = ',' . $request["myData"];
        }
        else{
            $string = $request["myData"];
        }
        $position = strlen($json) - 1;
        $out = substr_replace( $json, $string, $position, 0 );
        file_put_contents('neural.json', $out);
    }

    public function ajaxPhoto(Request $request)
    {
        $image = $request["image"];

        $image_parts = explode(";base64,", $image);

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'foto_face_recognition/' . $request["path"] . '.png';

        Storage::put($fileName, $image_base64);

        $user = User::query()->where('username', $request['path'])->update(["foto_face_recognition" => $fileName]);
        return $user;
    }

    public function editPasswordProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:6|max:255',
        ]);

        $validatedData['password'] = Hash::make($request->password);

        User::query()->where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Password Berhasil Diganti');
        return redirect('/pegawai');
    }

    public function shift($id, Request $request)
    {
        $mapping_shift = MappingShift::query()->where('user_id', $id)
                                    ->orderBy('tanggal', 'DESC')
                                    ->paginate(31)
                                    ->withQueryString();

        if($request["mulai"] === null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] === null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
        //    Log::info('masuk');
            $mapping_shift = MappingShift::query()->where('user_id', $id)
                                    ->whereBetween('tanggal', [$request["mulai"], $request["akhir"]])
                                    ->orderBy('tanggal', 'DESC')
                                    ->paginate(31)
                                    ->withQueryString();
        }

        return view('karyawan.mappingshift', [
            'title' => 'Mapping Shift',
            'karyawan' => User::query()->find($id),
            'shift_karyawan' => $mapping_shift,
            'shift' => Shift::all()
        ]);


    }

    public function dinasLuar($id, Request $request)
    {
        $dinas_luar = dinasLuar::query()->where('user_id', $id)
                        ->orderBy('tanggal', 'DESC')
                        ->paginate(31)
                        ->withQueryString();

        if($request["mulai"] === null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] === null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
        //    Log::info('masuk');

        $dinas_luar = dinasLuar::query()->where('user_id', $id)
                ->whereBetween('tanggal', [$request["mulai"], $request["akhir"]])
                ->orderBy('tanggal', 'DESC')
                ->paginate(31)
                ->withQueryString();
        }

        return view('karyawan.dinasluar', [
            'title' => 'Mapping Dinas Luar',
            'karyawan' => User::query()->find($id),
            'dinas_luar' => $dinas_luar,
            'shift' => Shift::all()
            ]);
        }

    /**
     * @throws \DateMalformedStringException
     * @throws \DateMalformedPeriodStringException
     */
//    public function prosesTambahShift(Request $request)
//    {
//        date_default_timezone_set('Asia/Jakarta');
//
//        if($request["tanggal_mulai"] === null) {
//            $request["tanggal_mulai"] = $request["tanggal_akhir"];
//        }
//
//        if($request["tanggal_akhir"] === null) {
//            $request["tanggal_akhir"] = $request["tanggal_mulai"];
//        }
//
//        $userId = $request['user_id'];
//        $tglMulai = $request['tanggal_mulai'];
//        $tglAkhir = $request['tanggal_akhir'];
//
//        $isAvailable = $this->karyawanService->isCutiIzinScheduleAvailable($userId, $tglMulai, $tglAkhir);
//
//        if(!$isAvailable) {
//            Alert::error('Peringatan!', 'Tanggal "'.$tglMulai.' sampai '.$tglAkhir.'" sudah ada ajuan Cuti/Izin');
//            return redirect('/cuti');
//        }
//
//        $request->validate([
//            'shift_id' => 'required',
//            'tanggal_mulai' => 'required',
//            'tanggal_akhir' => 'required',
//        ]);
//
//        $begin = new \DateTime($request["tanggal_mulai"]);
//        $end = new \DateTime($request["tanggal_akhir"]);
//        $end = $end->modify('+1 day');
//
//        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
//        $daterange = new \DatePeriod($begin, $interval ,$end);
//
//
//        foreach ($daterange as $date) {
//            $tanggal = $date->format("Y-m-d");
//
//            $cek = MappingShift::query()->where('user_id', $request['user_id'])->where('tanggal', $tanggal)->first();
//
//            if (!$cek) {
//                if ($request["shift_id"] === 1) {
//                    $request["status_absen"] = "Libur";
//                } else {
//                    $request["status_absen"] = "Tidak Masuk";
//                }
//
//                $request["tanggal"] = $tanggal;
//
//                $validatedData = $request->validate([
//                    'user_id' => 'required',
//                    'shift_id' => 'required',
//                    'tanggal' => 'required',
//                    'status_absen' => 'required',
//                ]);
//
//                $validatedData['lock_location'] = $request['lock_location'] ?: null;
//                $validatedData['telat'] = 0;
//                $validatedData['pulang_cepat'] = 0;
//
//                MappingShift::create($validatedData);
//            }
//        }
//        return redirect('/pegawai/shift/' . $request["user_id"])->with('success', 'Data Berhasil di Tambahkan');
//    }

    public function prosesTambahShift(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        if ($request["tanggal_mulai"] === null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        }

        if ($request["tanggal_akhir"] === null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        }

        $userId = $request['user_id'];
        $tglMulai = $request['tanggal_mulai'];
        $tglAkhir = $request['tanggal_akhir'];

        $isAvailable = $this->karyawanService->isCutiIzinScheduleAvailable($userId, $tglMulai, $tglAkhir);

        if (!$isAvailable) {
            Alert::error('Peringatan!', 'Tanggal "'.$tglMulai.' sampai '.$tglAkhir.'" sudah ada ajuan Cuti/Izin');
            return redirect('/cuti');
        }

        $request->validate([
            'shift_id' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
        ]);

        $begin = new \DateTime($tglMulai);
        $end   = new \DateTime($tglAkhir);
        $end   = $end->modify('+1 day');

        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);

        // Hitung selisih hari
        $jumlahHari = (new \DateTime($tglMulai))->diff(new \DateTime($tglAkhir))->days + 1;

        foreach ($daterange as $date) {

            $tanggal = $date->format("Y-m-d");
            $hariIni = $date->format("w"); // 0 = Minggu

            $cek = MappingShift::query()
                ->where('user_id', $userId)
                ->where('tanggal', $tanggal)
                ->first();

            if (!$cek) {

                // RULE BARU: Jika lebih dari 7 hari → hari Minggu otomatis Libur
                if ($jumlahHari > 7 && $hariIni == 0) {
                    $shiftId = 1; // shift libur
                    $statusAbsen = "Libur";
                } else {
                    $shiftId = $request["shift_id"];

                    if ($shiftId == 1) {
                        $statusAbsen = "Libur";
                    } else {
                        $statusAbsen = "Tidak Masuk";
                    }
                }

                MappingShift::create([
                    'user_id' => $userId,
                    'shift_id' => $shiftId,
                    'tanggal' => $tanggal,
                    'status_absen' => $statusAbsen,
                    'lock_location' => $request['lock_location'] ?: null,
                    'telat' => 0,
                    'pulang_cepat' => 0,
                ]);
            }
        }

        return redirect('/pegawai/shift/' . $userId)->with('success', 'Data Berhasil di Tambahkan');
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \DateMalformedPeriodStringException
     */
    public function prosesTambahDinas(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        if($request["tanggal_mulai"] === null) {
            $request["tanggal_mulai"] = $request["tanggal_akhir"];
        }

        if($request["tanggal_akhir"] === null) {
            $request["tanggal_akhir"] = $request["tanggal_mulai"];
        }

        $userId = $request['user_id'];
        $tglMulai = $request['tanggal_mulai'];
        $tglAkhir = $request['tanggal_akhir'];

        $isAvailable = $this->karyawanService->isCutiIzinScheduleAvailable($userId, $tglMulai, $tglAkhir);

        if(!$isAvailable) {
            Alert::error('Peringatan!', 'Tanggal "'.$tglMulai.' sampai '.$tglAkhir.'" sudah ada ajuan Cuti/Izin');
            return redirect('/cuti');
        }

        $begin = new \DateTime($request["tanggal_mulai"]);
        $end = new \DateTime($request["tanggal_akhir"]);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D'); //referensi : https://en.wikipedia.org/wiki/ISO_8601#Durations
        $daterange = new \DatePeriod($begin, $interval ,$end);


        foreach ($daterange as $date) {
            $tanggal = $date->format("Y-m-d");

            if ($request["shift_id"] === 1) {
                $request["status_absen"] = "Libur";
            } else {
                $request["status_absen"] = "Tidak Masuk";
            }

            $request["tanggal"] = $tanggal;

            $validatedData = $request->validate([
                'user_id' => 'required',
                'shift_id' => 'required',
                'tanggal' => 'required',
                'status_absen' => 'required',
            ]);

            dinasLuar::query()->create($validatedData);
        }
        return redirect('/pegawai/dinas-luar/' . $request["user_id"])->with('success', 'Data Berhasil di Tambahkan');
    }

    public function deleteShift(Request $request, $id)
    {
        $delete = MappingShift::query()->find($id);

        $delete?->delete();

        return redirect('/pegawai/shift/' . $request["user_id"])->with('success', 'Data Berhasil di Delete');
    }

    public function deleteDinas(Request $request, $id)
    {
        $delete = dinasLuar::query()->find($id);

        $delete?->delete();

        return redirect('/pegawai/dinas-luar/' . $request["user_id"])->with('success', 'Data Berhasil di Delete');
    }

    public function editShift($id)
    {
        return view('karyawan.editshift', [
            'title' => 'Edit Shift',
            'shift_karyawan' => MappingShift::query()->find($id),
            'shift' => Shift::all()
        ]);
    }

    public function editDinas($id)
    {
        return view('karyawan.editdinas', [
            'title' => 'Edit Dinas',
            'dinas_luar' => dinasLuar::query()->find($id),
            'shift' => Shift::all()
        ]);
    }

    public function prosesEditShift(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');


        if ($request["shift_id"] === 1) {
            $request["status_absen"] = "Libur";
        } else {
            $request["status_absen"] = "Tidak Masuk";
        }

        $validatedData = $request->validate([
            'shift_id' => 'required',
            'tanggal' => 'required',
            'status_absen' => 'required'
        ]);

        $validatedData['lock_location'] = $request['lock_location'] ?: null;

        MappingShift::query()->where('id', $id)->update($validatedData);
        return redirect('/pegawai/shift/' . $request["user_id"])->with('success', 'Data Berhasil di Update');
    }

    public function prosesEditDinas(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');


        if ($request["shift_id"] === 1) {
            $request["status_absen"] = "Libur";
        } else {
            $request["status_absen"] = "Tidak Masuk";
        }

        $validatedData = $request->validate([
            'shift_id' => 'required',
            'tanggal' => 'required',
            'status_absen' => 'required'
        ]);

        dinasLuar::query()->where('id', $id)->update($validatedData);
        return redirect('/pegawai/dinas-luar/' . $request["user_id"])->with('success', 'Data Berhasil di Update');
    }

    public function myProfile()
    {
        if (auth()->user()->is_admin === 'admin') {
            return view('karyawan.myprofile', [
                'title' => 'My Profile',
                'data_jabatan' => Jabatan::all()
            ]);

        }

        return view('karyawan.myprofileuser', [
            'title' => 'My Profile',
            'data_jabatan' => Jabatan::all()
        ]);
    }

    public function myProfileUpdate(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'foto_karyawan' => 'image|file|max:10240',
            'telepon' => 'required',
            'password' => 'required',
            'tgl_lahir' => 'required',
            'gender' => 'required',
            'tgl_join' => 'required',
            'status_nikah' => 'required',
            'alamat' => 'required',
            'rekening' => 'required',
        ];


        $userId = User::query()->find($id);

        if ($request->email !== $userId->email) {
            $rules['email'] = 'required|email:dns|unique:users';
        }

        if ($request->username !== $userId->username) {
            $rules['username'] = 'required|max:255|unique:users';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('foto_karyawan')) {
            if ($request->foto_karyawan_lama) {
                Storage::delete($request->foto_karyawan_lama);
            }
            $validatedData['foto_karyawan'] = $request->file('foto_karyawan')->store('foto_karyawan', 'public');
        }

        $path = public_path('neural.json');
        $neural = File::query()->get($path);
        $dataface = json_decode($neural, true);

        foreach ($dataface as &$item) {
            if ($item['label'] === $userId->username) {
                $item['label'] = $request->username;
            }
        }
        File::put($path, json_encode($dataface, JSON_PRETTY_PRINT));

        User::query()->where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/my-profile');
    }

    public function editPassMyProfile()
    {
        if (auth()->user()->is_admin === 'admin') {
            return view('karyawan.editpassmyprofile', [
                'title' => 'Ganti Password'
            ]);
        }

        return view('karyawan.editpassworduser', [
            'title' => 'Ganti Password'
        ]);

    }

    public function editPassMyProfileProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:6|max:255|confirmed',
        ]);

        $validatedData['password'] = Hash::make($request->password);

        User::query()->where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Password Berhasil di Update');
        return redirect('/dashboard');
    }

    public function resetCuti()
    {
        return view('karyawan.masterreset', [
            'title' => 'Master Data Reset Cuti',
            'data_cuti' => ResetCuti::query()->first()
        ]);
    }

    public function resetCutiProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'izin_cuti' => 'required',
            'izin_dinas_luar' => 'required',
            'izin_sakit' => 'required',
            'izin_cek_kesehatan' => 'required',
            'izin_keperluan_pribadi' => 'required',
            'izin_lainnya' => 'required',
            'izin_telat' => 'required',
            'izin_pulang_cepat' => 'required'
        ]);

        ResetCuti::query()->where('id', $id)->update($validatedData);
        return redirect('/reset-cuti')->with('success', 'Master Cuti Berhasil Diupdate');
    }

    private function getPendingUsersAbsence($type, $startRange, $endRange, $timeField)
    {
        try {
            $now = Carbon::now();

            // Divider for starting log
            Log::info("========== ({$type}) ==========");

            // Log debug info for safety and tracking
            Log::info("Query Details:", [
                'startRange'  => $startRange,
                'endRange'    => $endRange,
                'startTime'   => $now->copy()->addMinutes($startRange)->format('H:i:s'),
                'endTime'     => $now->copy()->addMinutes($endRange)->format('H:i:s'),
                'currentDate' => $now->toDateString(),
            ]);

            // Define exact match logic for 'shift-start' and 'shift-end'
            $isShiftExactMatch = in_array($type, ['shift-start', 'shift-end']);

            $pendingAbsence = MappingShift::query()->where('status_absen', '=', 'Tidak Masuk')
                ->where('tanggal', '=', $now->toDateString())
                ->whereHas('Shift', function ($query) use ($now, $startRange, $endRange, $timeField, $isShiftExactMatch) {
                    if ($isShiftExactMatch) {
                        // Exact match logic for shift-start and shift-end
                        $query->whereRaw("TIME({$timeField}) >= ?", $now->copy()->startOfMinute()->format('H:i:s'))
                            ->whereRaw("TIME({$timeField}) < ?", $now->copy()->startOfMinute()->addMinute()->format('H:i:s'));
                    } else {
                        // Default time range logic for other scenarios
                        $query->whereTime($timeField, '>=', $now->copy()->addMinutes($startRange)->format('H:i:s'))
                            ->whereTime($timeField, '<=', $now->copy()->addMinutes($endRange)->format('H:i:s'));
                    }
                })
                ->pluck('user_id');
            $pendingUsersAbsence = [];
            foreach ($pendingAbsence as $user_id) {
                $pendingUsersAbsenceDummy = User::query()->where('id', $user_id)->get();
                foreach ($pendingUsersAbsenceDummy as $pendingUsersAbsenceDummyDetails) {
                    $pendingUsersAbsence[$pendingUsersAbsenceDummyDetails['id']] = $pendingUsersAbsenceDummy;
                }
            }

            // Check if there are pending absences and log accordingly
            if (!$pendingUsersAbsence) {
                Log::info("No pending absences found for type: {$type}");
            }
            else {
                Log::info("Pending absences count for {$type}: " . count($pendingUsersAbsence));
//                Log::info("Pending absences details:", $pendingAbsences->toArray());
            }

            // Divider for ending log
            Log::info("==============================");

            // Return the result
            return $pendingUsersAbsence;

        } catch (\Exception $e) {
            // Log the error with a divider and message
            Log::error("===== ERROR in getPendingAbsences ({$type}) =====");
            Log::error("Error in getPendingAbsences: {$e->getMessage()}");

            // Return an empty collection to prevent breaking the flow
            return collect([]);
        }
    }

//    private function notifyUsers($users, $messageTemplate, $type, $url)
//    {
//        try {
//            // Check if users collection is empty
//            if ($users->isEmpty()) {
//                Log::info("No users to notify for type {$type}");
//                return; // Exit the method early, no users to process
//            }
//
//            foreach ($users as $mapping) {
//                $user = $mapping->user;
//
//                // Validate that the user exists
//                if ($user) {
//                    try {
//                        // Prepare notification message
//                        $notif = str_replace('{name}', $user->name, $messageTemplate);
//
//                        // Set additional data for the notification
//                        $user->messages = [
//                            'user_id' => 1, // Default Admin ID
//                            'from'    => 'Admin',
//                            'message' => $notif,
//                            'action'  => $url
//                        ];
//
//                        // Send notification
//                        $user->notify(new \App\Notifications\UserNotification);
//
//                        // Log notification info
//                        Log::info("Notification sent to user ID: {$user->id}, type: {$type}");
//
//                        // Dispatch event
//                        NotifApproval::dispatch($type, $user->id, $notif, $url);
//
//                    } catch (\Exception $e) {
//                        // Handle notification-specific errors (e.g., notify, dispatch issues)
//                        Log::error("Error sending notification to user ID {$user->id}: " . $e->getMessage());
//                    }
//                } else {
//                    // Log when user is not found for a mapping
//                    Log::warning("User not found for MappingShift ID {$mapping->id}");
//                }
//            }
//
//        } catch (\Exception $e) {
//            // Handle any unexpected error in the main notifyUsers loop
//            Log::error("Error in notifyUsers for type {$type}: " . $e->getMessage());
//        }
//    }

//    private function sendPendingAbsensiEmails($users, $messageTemplate, $type, $url, $executionTime)
//    {
//        try {
//            if ($users->isEmpty()) {
//                Log::info("No users to email for type {$type}");
//                return;
//            }
//
//            // Determine greeting based on executionTime
//            $hour = \Carbon\Carbon::parse($executionTime)->hour;
//            $greeting = '';
//
//            if ($hour >= 5 && $hour < 11) {
//                $greeting = 'Selamat Pagi!';
//            } elseif ($hour >= 11 && $hour < 15) {
//                $greeting = 'Selamat Siang!';
//            } elseif ($hour >= 15 && $hour < 18) {
//                $greeting = 'Selamat Sore!';
//            } else {
//                $greeting = 'Selamat Malam!';
//            }
//
//            foreach ($users as $mapping) {
//                $user = $mapping->user;
//
//                if ($user) {
//                    try {
//                        // Prepare email content
//                        $notif = str_replace('{name}', $user->name, $messageTemplate);
//
//                        // Send email
//                        Mail::to($user->email)->send(new PendingAbsensiMail($user, $type, $notif, $url, $greeting));
//
//                        // Log email info
//                        Log::info("Email sent to user ID: {$user->id}, email: {$user->email}");
//
//                    } catch (\Exception $e) {
//                        Log::error("Error sending email to user ID {$user->id}: " . $e->getMessage());
//                    }
//                } else {
//                    Log::warning("User not found for MappingShift ID {$mapping->id}");
//                }
//            }
//        } catch (\Exception $e) {
//            Log::error("Error in sendPendingAbsensiEmails for type {$type}: " . $e->getMessage());
//        }
//    }

    public function notifyPendingAbsensi(): \Illuminate\Http\JsonResponse
    {
        try {
            $executionTime = now()->format('H:i:s'); // Format the current time for logging

            // Log the start of the entire execution
//            Log::info("===== NOTIFICATION {$executionTime} EXECUTION STARTED =====");

            // Define notification scenarios
            $notificationSchedules = [
                'check-in-10-minute' => [
                    'type' => 'check-in-10-minute',
                    'startRange' => 9,
                    'endRange' => 10,
                    'timeField' => 'jam_masuk',
                    'messageTemplate' => "Hai {name}, jadwal absensi Anda akan dimulai dalam 10 menit. Harap segera hadir!",
                    'url' => url('/absen'),
                    'executionTime' => $executionTime
                ],
                'shift-start' => [
                    'type' => 'shift-start',
                    'startRange' => 0,
                    'endRange' => 1,
                    'timeField' => 'jam_masuk',
                    'messageTemplate' => "Hai {name}, jadwal kerja Anda telah dimulai! Harap segera melakukan absensi.",
                    'url' => url('/absen'),
                    'executionTime' => $executionTime
                ],
                'check-out-10-minute' => [
                    'type' => 'check-out-10-minute',
                    'startRange' => 9,
                    'endRange' => 10,
                    'timeField' => 'jam_keluar',
                    'messageTemplate' => "Hai {name}, Jadwal absensi Anda akan segera berakhir dalam 10 menit. Segera absen!",
                    'url' => url('/absen'),
                    'executionTime' => $executionTime
                ],
                'check-out-5-minute' => [
                    'type' => 'check-out-5-minute',
                    'startRange' => 4,
                    'endRange' => 5,
                    'timeField' => 'jam_keluar',
                    'messageTemplate' => "Hai {name}, Jadwal absensi Anda akan segera berakhir dalam 5 menit. Harap segera absen!",
                    'url' => url('/absen'),
                    'executionTime' => $executionTime
                ],
                'shift-end' => [
                    'type' => 'shift-end',
                    'startRange' => 0,
                    'endRange' => 1,
                    'timeField' => 'jam_keluar',
                    'messageTemplate' => "Hai {name}, jadwal kerja Anda telah selesai! Namun Anda belum absen, silahkan hubungi Admin! Jangan lupa untuk menyelesaikan semua pekerjaan sebelum meninggalkan kantor.",
                    'url' => url('/dashboard'),
                    'executionTime' => $executionTime
                ]
            ];

            // Fetch user sender data
            $userSender = User::query()->select("id", "name")
                ->where("is_admin", "admin")->first();

            $scenario = [];

            // Process notifications based on defined scenarios
            foreach ($notificationSchedules as $schedule) {

                $pendingUsersAbsence = $this->getPendingUsersAbsence(
                    $schedule['type'],
                    $schedule['startRange'],
                    $schedule['endRange'],
                    $schedule['timeField']
                );

                if ($pendingUsersAbsence) {
                    foreach ($pendingUsersAbsence as $pendingUserAbsence => $details) {
                    Log::info("{$schedule['type']} Notifications Sent:", $details->toArray());
                    // Insert each scenario into the array
                    $scenario[$pendingUserAbsence] = [
                        'type' => $schedule['type'],
                        'dataType' => 'arrays of object',
                        'sender' => $userSender,
                        'receivers' => $details,
                        'messageTemplate' => $schedule['messageTemplate'],
                        'url' => $schedule['url'],
                        'executionTime' => $schedule['executionTime']
                    ];

//                    $this->notifyService->sendNotifies($pendingAbsen,[
//                        'type' => $schedule['type'],
//                        'sender' => $userSender,
//                        'receivers' => $pendingAbsen,
//                        'messageTemplate' => $schedule['messageTemplate'],
//                        'url' => $schedule['url'],
//                        'executionTime' => $schedule['executionTime']
//                    ]);
//                    $this->emailService->sendEmails([
//                        'type' => $scenario['type'],
//                        'sender' => $userSender,
//                        'messageTemplate' => $scenario['messageTemplate'],
//                        'url' => $scenario['url'],
//                        'executionTime' => $scenario['executionTime']
//                    ], function($user, $type, $notif, $url, $greeting) {
//                        Mail::to($user->email)->send(new PendingAbsensiMail($user, $type, $notif, $url, $greeting));
//                    });
                    }

                    // Break to notify only one type at a time
                    break;
                }
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
                $this->emailService->sendEmails($scenario, function($user, $type, $notif, $url, $greeting) {
                    Mail::to($user->email)->send(new PendingAbsensiMail($user, $type, $notif, $url, $greeting));
                });
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
                Alert::error('Error', 'An error occurred, while sending email. Please try again later.');
            }

            // Log the end of the entire execution
//            Log::info("===== NOTIFICATION {$executionTime} EXECUTION ENDED =====");
            Log::info("=================================================");

        } catch (\Exception $e) {
            Log::error("Error in notifyPendingAbsensi: {$e->getMessage()}");
            Log::info("===== NOTIFICATION EXECUTION ENDED WITH ERRORS =====");
        }

        return response()->json(['message' => 'Pending absensi notifications checked.']);
    }
}
