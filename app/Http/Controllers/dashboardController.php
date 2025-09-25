<?php

namespace App\Http\Controllers;
use App\Models\Oncall;
use App\Models\settings;
use App\Models\User;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\Lembur;
use App\Models\ResetCuti;
use Illuminate\Support\Facades\Log;

class dashboardController extends Controller
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tgl_skrg = date("Y-m-d");

        if(auth()->user()->is_admin == "admin"){
            $tahun_skrg = date('Y');
            $bulan_skrg = date('m');
            $jmlh_bulan = cal_days_in_month(CAL_GREGORIAN, $bulan_skrg, $tahun_skrg);
            $tgl_mulai = date('1945-01-01');
            $tgl_akhir = date('Y-m-'.$jmlh_bulan);
            $user = User::select('name', 'tgl_lahir')
                ->whereBetween('tgl_lahir', [$tgl_mulai, $tgl_akhir])->get();
            $cuti = Cuti::select('user_id', 'tanggal_mulai')
                ->where('status_cuti', 'Diterima')
                ->whereBetween('tanggal_mulai', [$tgl_mulai, $tgl_akhir])->get();

            return view('dashboard.index', [
                'title' => 'Dashboard',
                'settings' => settings::first(),
                'data_user' => $user,
                'data_cuti' => $cuti,
                'jumlah_user' => User::count(),
                'jumlah_masuk' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Masuk')->count(),
                'jumlah_libur' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Libur')->count(),
                'jumlah_cuti' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Cuti')->count(),
                'jumlah_izin_masuk' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Masuk')->count(),
                'jumlah_izin_telat' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Telat')->count(),
                'jumlah_izin_pulang_cepat' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count(),
                'jumlah_karyawan_lembur' => Lembur::where('tanggal', $tgl_skrg)->count(),
            ]);
        } else {
            // Get the logged-in user's ID
            $user_login = auth()->user()->id;
            $tglskrg = date('Y-m-d');
            $tglkmrn = date('Y-m-d', strtotime('-1 days'));
            $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal', $tglkmrn)->get();
            if($mapping_shift->count() > 0) {
                foreach($mapping_shift as $mp) {
                    $jam_absen = $mp->jam_absen;
                    $jam_pulang = $mp->jam_pulang;
                }
            } else {
                $jam_absen = "-";
                $jam_pulang = "-";
            }
            if($jam_absen != null && $jam_pulang == null) {
                $tanggal = $tglkmrn;
            } else {
                $tanggal = $tglskrg;
            }

            // Define the forms and their corresponding session keys
            $forms = [
                'cuti' => ['cuti_user_id', 'cuti_mulai', 'cuti_akhir'],
                'lembur' => ['lembur_user_id', 'lembur_mulai', 'lembur_akhir'],
                'oncall' => ['oncall_user_id', 'oncall_mulai', 'oncall_akhir']
            ];

            // Initialize variables
            $user_ids = [];
            $mulais = [];
            $akhirs = [];

            // Retrieve session values
            foreach ($forms as $key => $fields) {
                $user_ids[$key] = session($fields[0]) ?? null;
                $mulais[$key] = session($fields[1]) ?? null;
                $akhirs[$key] = session($fields[2]) ?? null;
            }

            // get auth user role
            $role = auth()->user()->is_admin;

            // Get user data cuti based on role
            $user = $this->getUserDataByRole($user_login, $role);
            // Get cuti query based on role and parameters
            $queryCuti = $this->getCutiQuery($user_login, $role, $user_ids['cuti'], $mulais['cuti'], $akhirs['cuti']);
            // Paginate the results
            $cuti = $queryCuti->orderBy('id', 'desc')->paginate(2)->withQueryString();
            // Determine the approval type based on role
            $approvalType = $role == 'director' ? 'approval3' : ($role == 'manager' ? 'approval2' : 'approval1');

            // Get lembur query for head
            $queryLembur = $this->getLemburQuery($user_login, $role, $user_ids['lembur'], $mulais['lembur'], $akhirs['lembur']);
            // Paginate the results
            $lembur = $queryLembur->orderBy('id', 'desc')->paginate(2)->withQueryString();

            // Get Oncall query for head
            $queryOncall = $this->getOncallQuery($user_login, $role, $user_ids['oncall'], $mulais['oncall'], $akhirs['oncall']);
            // Paginate the results
            $oncall = $queryOncall->orderBy('id', 'desc')->paginate(2)->withQueryString();

            return view('dashboard.indexUser', [
                'title' => 'Dashboard',
                'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal', $tanggal)->first(),
                'data_cuti_user' => $cuti,
                'data_lembur_user' => $lembur,
                'data_oncall_user' => $oncall,
                'user' => $user,
                'approvalType' => $approvalType
            ]);
        }
    }

    // Get user data based on role
    private function getUserDataByRole($user_login, $role) {
        if ($role == 'head') {
            // Get users managed by the head
            return User::whereHas('jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            })->get();
        } elseif ($role == 'manager') {
            // Get head IDs managed by the manager
            $headIds = User::whereHas('jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            })->pluck('id');

            // Get users managed by the heads
            return User::whereHas('jabatan', function ($query) use ($headIds) {
                $query->whereIn('manager', $headIds);
            })->orWhereIn('id', $headIds)->get();
        } elseif ($role == 'director') {
            // Get manager IDs managed by the director
            $managerIds = User::whereHas('jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            })->pluck('id');

            // Get head IDs managed by the managers
            $headIds = User::whereHas('jabatan', function ($query) use ($managerIds) {
                $query->whereIn('manager', $managerIds);
            })->pluck('id');

            // Get manager's cuti data, head's cuti data approved by manager, and user's cuti data approved by head
            $userIds = Cuti::where(function ($query) use ($managerIds, $headIds) {
                $query->whereIn('user_id', $managerIds)
                    ->orWhereHas('user.jabatan', function ($query) use ($headIds) {
                        $query->whereIn('manager', $headIds)
                            ->where('approval1', 'Diterima');
                    })
                    ->orWhereHas('user.jabatan', function ($query) use ($managerIds) {
                        $query->whereIn('manager', $managerIds)
                            ->where('approval2', 'Diterima');
                    });
            })->pluck('user_id')->unique();

            // Get users based on the user IDs
            return User::whereIn('id', $userIds)->get();
        }

        return collect();
    }

    // Get cuti query based on role and parameters
    private function getCutiQuery($user_login, $role, $user_id, $mulai, $akhir) {
        // Initialize an empty query builder for the results
        $query = Cuti::query();

        if ($role == 'head') {
            // Filter cuti data for users managed by the head
            $query->whereHas('user.jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            });
        }
        elseif ($role == 'manager') {
            // Get head IDs managed by the manager
            $headIds = User::whereHas('jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            })->pluck('id');

            // Filter cuti data for both head's and user's approved by the head
            $query->where(function ($query) use ($headIds) {
                $query->whereIn('user_id', $headIds)
                    ->orWhereHas('user.jabatan', function ($query) use ($headIds) {
                        $query->whereIn('manager', $headIds)
                            ->where('approval1', 'Diterima');
                    });
            });
        }
        elseif ($role == 'director') {
            // Get manager IDs managed by the director
            $managerIds = User::whereHas('jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            })->pluck('id');

            // Get head IDs managed by the managers
            $headIds = User::whereHas('jabatan', function ($query) use ($managerIds) {
                $query->whereIn('manager', $managerIds);
            })->pluck('id');

            // Filter cuti data for manager's, head's approved by manager, and user's approved by head
            $query->where(function ($query) use ($managerIds, $headIds) {
                $query->whereIn('user_id', $managerIds)
                    ->orWhereHas('user.jabatan', function ($query) use ($headIds) {
                        $query->whereIn('manager', $headIds)
                            ->where('approval1', 'Diterima');
                    })
                    ->orWhereHas('user.jabatan', function ($query) use ($managerIds) {
                        $query->whereIn('manager', $managerIds)
                            ->where('approval2', 'Diterima');
                    });
            });
        }

        // Filter by user ID if provided
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        // Filter by date range if provided
        if ($mulai && $akhir) {
            $query->where(function ($query) use ($mulai, $akhir) {
                $query->whereBetween('tanggal_mulai', [$mulai, $akhir])
                    ->orWhereBetween('tanggal_akhir', [$mulai, $akhir])
                    ->orWhere(function ($query) use ($mulai, $akhir) {
                        $query->where('tanggal_mulai', '<=', $mulai)
                            ->where('tanggal_akhir', '>=', $akhir);
                    });
            });
        }

        return $query;
    }

    private function getLemburQuery($user_login, $role, $user_id, $mulai, $akhir) {
        // Initialize an empty query builder for the results
        $query = Lembur::query();

        // Filter lembur data for users managed by the head
        if ($role == 'head') {
            $query->whereHas('user.jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            });
        }

        // Filter by user ID if provided
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        // Filter by date range if provided
        if ($mulai && $akhir) {
            $query->where(function ($query) use ($mulai, $akhir) {
                $query->whereBetween('tanggal', [$mulai, $akhir])
                    ->orWhereBetween('tanggal', [$mulai, $akhir])
                    ->orWhere(function ($query) use ($mulai, $akhir) {
                        $query->where('tanggal', '<=', $mulai)
                            ->where('tanggal', '>=', $akhir);
                    });
            });
        }

        return $query;
    }

    private function getOncallQuery($user_login, $role, $user_id, $mulai, $akhir) {
        // Initialize an empty query builder for the results
        $query = Oncall::query();

        // Filter Oncall data for users managed by the head
        if ($role == 'head') {
            $query->whereHas('user.jabatan', function ($query) use ($user_login) {
                $query->where('manager', $user_login);
            });
        }

        // Filter by user ID if provided
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        // Filter by date range if provided
        if ($mulai && $akhir) {
            $query->where(function ($query) use ($mulai, $akhir) {
                $query->whereBetween('tanggal', [$mulai, $akhir])
                    ->orWhereBetween('tanggal', [$mulai, $akhir])
                    ->orWhere(function ($query) use ($mulai, $akhir) {
                        $query->where('tanggal', '<=', $mulai)
                            ->where('tanggal', '>=', $akhir);
                    });
            });
        }

        return $query;
    }

    public function storeFormData(Request $request)
    {
        $forms = [
            'cuti' => ['cuti_user_id', 'cuti_mulai', 'cuti_akhir'],
            'lembur' => ['lembur_user_id', 'lembur_mulai', 'lembur_akhir'],
            'oncall' => ['oncall_user_id', 'oncall_mulai', 'oncall_akhir']
        ];

        foreach ($forms as $form => $fields) {
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    session([$field => $request->input($field)]);
                }
            }
        }

        return redirect()->route('dashboard');
    }

    public function clearFormSession(Request $request)
    {
        $fields = [
            'cuti_user_id', 'cuti_mulai', 'cuti_akhir',
            'lembur_user_id', 'lembur_mulai', 'lembur_akhir',
            'oncall_user_id', 'oncall_mulai', 'oncall_akhir'
        ];

        foreach ($fields as $field) {
            $request->session()->forget($field);
        }

        return redirect()->route('dashboard');
    }

    public function menu()
    {
        return view('dashboard.menu', [
            'title' => 'All Menu',
        ]);
    }
}
