<?php

namespace App\Http\Controllers;

use App\Mail\ApprovalLemburMail;
use App\Models\User;
use App\Models\Lembur;
use App\Services\EmailService;
use App\Services\NotifyService;
use Illuminate\Http\Request;
use App\Events\NotifApproval;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class LemburController extends Controller
{
    protected $notifyService;
    protected $emailService;

    public function __construct(NotifyService $notifyService, EmailService $emailService)
    {
        $this->notifyService = $notifyService;
        $this->emailService = $emailService;
    }

    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date("Y-m-d");
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $lembur = Lembur::query()->where('user_id', $user_login)->where('tanggal', $tglkmrn)->get();
        if($lembur->count() > 0) {
            foreach($lembur as $l) {
                $jam_keluar = $l->jam_keluar;
            }
        } else {
            $jam_keluar = "-";
        }
        if($jam_keluar === null){
            $tanggal = $tglkmrn;
        } else {
            $tanggal = $tglskrg;
        }

        if (auth()->user()->is_admin === 'admin') {
            return view('lembur.index', [
                'title' => 'Absen Lembur Admin',
                'lembur' => Lembur::query()->where('user_id', $user_login)->where('tanggal', $tanggal)->get()
            ]);
        }

        return view('lembur.indexUser', [
            'title' => 'Absen Lembur Karyawan',
            'lembur' => Lembur::query()->where('user_id', $user_login)->where('tanggal', $tanggal)->first()
        ]);

    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit === "K") {
            return ($miles * 1.609344);
        } else if ($unit === "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function masuk(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');

        $lat_kantor = auth()->user()->Lokasi->lat_kantor;
        $long_kantor = auth()->user()->Lokasi->long_kantor;
        $radius = auth()->user()->Lokasi->radius;
        $nama_lokasi = auth()->user()->Lokasi->nama_lokasi;

        $request["jarak_masuk"] = $this->distance($request["lat_masuk"], $request["long_masuk"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_masuk"] > $radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius ' . $nama_lokasi);
        } else {
            $foto_jam_masuk = $request["foto_jam_masuk"];

            $image_parts = explode(";base64,", $foto_jam_masuk);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_masuk_lembur/' . uniqid('', true) . '.png';

            Storage::disk('public')->put($fileName, $image_base64);

            $request["foto_jam_masuk"] = $fileName;

            $validatedData = $request->validate([
                'user_id' => 'required',
                'tanggal' => 'required',
                'jam_masuk' => 'required',
                'foto_jam_masuk' => 'required',
                'lat_masuk' => 'required',
                'long_masuk' => 'required',
                'jarak_masuk' => 'required',
                'keterangan' => 'required',
                'status' => 'required'
            ]);

            Lembur::query()->create($validatedData);

            $request->session()->flash('success', 'Berhasil Masuk Lembur');

        }
        return redirect('/lembur');

    }

    public function pulang(Request $request, $id)
    {
        date_default_timezone_set('Asia/Jakarta');

        $lat_kantor = auth()->user()->Lokasi->lat_kantor;
        $long_kantor = auth()->user()->Lokasi->long_kantor;
        $radius = auth()->user()->Lokasi->radius;
        $nama_lokasi = auth()->user()->Lokasi->nama_lokasi;

        $request["jarak_keluar"] = $this->distance($request["lat_keluar"], $request["long_keluar"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_keluar"] > $radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius ' . $nama_lokasi);
            return redirect('/lembur');
        } else {
            $foto_jam_keluar = $request["foto_jam_keluar"];

            $image_parts = explode(";base64,", $foto_jam_keluar);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_keluar_lembur/' . uniqid('', true) . '.png';

            Storage::disk('public')->put($fileName, $image_base64);

            $request["foto_jam_keluar"] = $fileName;

            $lembur = Lembur::find($id);

            $jam_masuk = $lembur->jam_masuk;
            $time_masuk = strtotime($jam_masuk);
            $time_keluar = strtotime($request["jam_keluar"]);

            $diff = $time_keluar - $time_masuk;

            $request["total_lembur"] = $diff;

            $validatedData = $request->validate([
                'jam_keluar' => 'required',
                'lat_keluar' => 'required',
                'long_keluar' => 'required',
                'jarak_keluar' => 'required',
                'foto_jam_keluar' => 'required',
                'total_lembur' => 'required'
            ]);

            Lembur::where('id', $id)->update($validatedData);

            // Fetch user sender data
            $userSender = User::query()->select("id", "name")
                ->where("id", auth()->user()->id)->first();

            // Fetch users to notify (admin and head) based on requested lembur user
            $requestedUser = User::query()->findOrFail($lembur->user_id);
            $users = User::query()->where('is_admin', 'admin')
                ->orWhere(function ($query) use ($requestedUser) {
                    $query->where('id', function ($subQuery) use ($requestedUser) {
                        $subQuery->select('manager')
                            ->from('jabatans')
                            ->where('id', $requestedUser->jabatan_id);
                    });
                })->get();

            $scenario = [];

            foreach ($users as $user) {
                $type = 'Approval';
                if ($user->is_admin == 'admin') {
                    $notif = 'Ada pengajuan Lembur Dari ' . auth()->user()->name;
                    $url = url('/data-lembur?user_id='.$user->id.'&mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal);
//                    $action = '/data-lembur?user_id='.$user->id.'&mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal;
                } else {
                    $notif = 'Ada pengajuan Lembur Dari ' . auth()->user()->name . ' Butuh Approval Anda';
                    $url = url('/dashboard?user_id='.$lembur->user_id.'&mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal);
//                    $action = '/dashboard?user_id='.$lembur->user_id.'&mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal;
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

//                $user->messages = [
//                    'user_id'   =>  auth()->user()->id,
//                    'from'   =>  auth()->user()->name,
//                    'message'   =>  $notif,
//                    'action'   =>  $action
//                ];
//                $user->notify(new \App\Notifications\UserNotification);
//
//                NotifApproval::dispatch($type, $user->id, $notif, $url);
//
//                Mail::to($user->email)->send(new ApprovalCutiMail($greeting, $user, $notif, $cuti, $url));
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
                    Mail::to($user->email)->send(new ApprovalLemburMail($greeting, $user, $notif, $url));
                });
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
                Alert::error('Error', 'An error occurred, while sending email. Please try again later.');
            }

            return redirect('/lembur')->with('success', 'Berhasil Pulang Lembur');
        }

    }

    public function dataLembur(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_lembur = Lembur::where('tanggal', $tglskrg);

        if($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["user_id"] && $request["mulai"] && $request["akhir"]) {
            $data_lembur = Lembur::where('user_id', $request["user_id"])->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        return view('lembur.datalembur', [
            'title' => 'Data Lembur',
            'user' => User::select('id', 'name')->get(),
            'data_lembur' => $data_lembur->paginate(10000)->withQueryString()
        ]);
    }

    public function myLembur(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tglskrg = date('Y-m-d');
        $data_lembur = Lembur::where('tanggal', $tglskrg)->where('user_id', auth()->user()->id);
        if($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_lembur = Lembur::where('user_id', auth()->user()->id)->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        if (auth()->user()->is_admin == 'admin') {
            return view('lembur.mylembur', [
                'title' => 'History Lembur',
                'data_lembur' => $data_lembur->paginate(10000000)->get()
            ]);
        } else {
            return view('lembur.mylemburuser', [
                'title' => 'History Lembur',
                'data_lembur' => $data_lembur->paginate(10000000)->withQueryString()
                ]);
        }
    }

    public function approval(Request $request, $id)
    {
        $request ['lembur_approved_by'] = auth()->user()->id;
        $lembur = Lembur::find($id);

        // Fetch user sender data
        $userSender = User::query()->select("id", "name")
            ->where("id", auth()->user()->id)->first();

        $requestedUser = User::query()->where('id', $lembur->user_id)
            ->orWhere('is_admin', 'admin')->get();
        $validated = $request->validate([
            'status_lembur' => 'required',
            'notes_lembur' => 'nullable',
            'lembur_approved_by' => 'required',
        ]);

        $lemburData = [
            'status' => $validated['status_lembur'],
            'notes' => $validated['notes_lembur'],
            'approved_by' => $validated['lembur_approved_by'],
        ];

        $scenario = [];

        foreach ($requestedUser as $user) {
            // Determine greeting based on executionTime
            $executionTime = now()->format('H:i:s'); // Format the current time for logging

            if ($request['status_lembur'] == 'Approved') {
                $type = 'Approved';
                $stat = 'Approve';
            } else {
                $type = 'Rejected';
                $stat = 'Reject';
            }

            if ($user->is_admin == 'admin') {
                if (auth()->user()->is_admin == 'head' && $lembur->User->is_admin == 'admin') {
                    $notif = 'Lembur Anda Telah Di ' . $type . ' Oleh ' . auth()->user()->name;
                } else {
                    $notif = 'Pengajuan Lembur Dari ' . $lembur->User->name . ' Telah Di ' . $type . ' Oleh ' . auth()->user()->name;
                }
                $url = url('/data-lembur?user_id=' . $user->id . '&mulai=' . $lembur->tanggal . '&akhir=' . $lembur->tanggal);
            } else {
                $notif = 'Lembur Anda Telah Di ' . $type . ' Oleh ' . auth()->user()->name;
                $url = url('/dashboard?lembur_user_id=' . $lembur->user_id . '&lembur_mulai=' . $lembur->tanggal . '&lembur_akhir=' . $lembur->tanggal);
            }

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
            $this->emailService->sendEmails($scenario, function ($greeting, $user, $notif, $url) {
                Mail::to($user->email)->send(new ApprovalLemburMail($greeting, $user, $notif, $url));
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            Alert::error('Error', 'An error occurred, while sending email. Please try again later.');
        }

         $lembur->update($lemburData);

        if (auth()->user()->is_admin != 'admin') {
            $path = 'dashboard';
            $params = [
                'lembur_user_id' => 'user_id',
                'lembur_mulai' => 'tanggal',
                'lembur_akhir' => 'tanggal'
            ];
        } else {
            $path = 'data-lembur';
            $params = [
                'user_id' => 'user_id',
                'mulai' => 'tanggal',
                'akhir' => 'tanggal'
            ];
        }

        // Store the values in the session using a foreach loop
        foreach ($params as $sessionKey => $lemburProperty) {
            session([$sessionKey => $lembur->$lemburProperty]);
        }

        return redirect()->route($path)->with('success', 'Berhasil ' . $stat . ' Lembur');
    }


}
