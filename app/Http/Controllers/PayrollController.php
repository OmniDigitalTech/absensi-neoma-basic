<?php

namespace App\Http\Controllers;

use App\Models\DataCuti;
use App\Models\User;
use App\Models\Payroll;
use App\Models\StatusPtkp;
use App\Services\KaryawanService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    protected $karyawanService;

    public function __construct(KaryawanService $karyawanService)
    {
        $this->middleware('auth');
        $this->karyawanService = $karyawanService;
    }

    public function index()
    {
        $bulan = request()->input('bulan');
        $tahun = request()->input('tahun');

        $query = Payroll::query();

        if (auth()->user()->is_admin !== 'admin') {
//            $data = Payroll::when($bulan, function ($query) use ($bulan) {
//                                return $query->where('bulan', $bulan);
//                            })
//                            ->when($tahun, function ($query) use ($tahun) {
//                                return $query->where('tahun', $tahun);
//                            })
//                            ->orderBy('no_gaji', 'DESC');
//
//            return view('payroll.index', [
//                'title' => 'Payroll',
//                'data' => $data->paginate(10)->withQueryString()
//            ]);
            $query->where('user_id', auth()->user()->id);
        }
//         else {
//        $data = Payroll::where('user_id', auth()->user()->id)
//                        ->when($bulan, function ($query) use ($bulan) {
//                            return $query->where('bulan', $bulan);
//                        })
//                        ->when($tahun, function ($query) use ($tahun) {
//                            return $query->where('tahun', $tahun);
//                        })
//                        ->orderBy('no_gaji', 'DESC');
//        }
        $data = $query->when($bulan, function ($q) use ($bulan) {
                            return $q->where('bulan', $bulan);
                        })
                        ->when($tahun, function ($q) use ($tahun) {
                            return $q->where('tahun', $tahun);
                        })
                        ->orderBy('no_gaji', 'DESC')
                        ->paginate(10)
                        ->withQueryString();

        $viewName = auth()->user()->is_admin === 'admin' ? 'payroll.index' : 'payroll.indexuser';
        $title = auth()->user()->is_admin === 'admin' ? 'Payroll' : 'Data Penggajian Karyawan';

//        return view('payroll.indexuser', [
//            'title' => 'Data Penggajian Karyawan',
//            'data' => $data->paginate(10)->withQueryString()
//        ]);
        return view($viewName, [
            'title' => $title,
            'data' => $data
        ]);
    }

    public function tambah()
    {
        return view('payroll.tambah', [
            'title' => 'Tambah Data Penggajian Karyawan',
            'data_user' => User::select('id', 'name')->orderBy('name', 'ASC')->get(),
            'data_status' => StatusPtkp::select('id', 'name')->orderBy('name', 'ASC')->get()
        ]);
    }

    public function tambahProses(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'status_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'gaji' => 'required',
            'setoran_bpjs_kes' => 'nullable',
            'tunjangan_bpjs_kes' => 'nullable',
            'setoran_bpjs_tk' => 'nullable',
            'tunjangan_bpjs_tk' => 'nullable',
            'tunjangan_pensiun' => 'nullable',
            'tunjangan_komunikasi' => 'nullable',
            'tunjangan_pph_21' => 'nullable',
            'pot_lainnya' => 'nullable',
            'lembur' => 'nullable'
        ]);

        if(!$validated['gaji']){
            $validated['gaji'] = 0;
        }

        if(!$validated['setoran_bpjs_kes']){
            $validated['setoran_bpjs_kes'] = 0;
        }

        if(!$validated['tunjangan_bpjs_kes']){
            $validated['tunjangan_bpjs_kes'] = 0;
        }

        if(!$validated['setoran_bpjs_tk']){
            $validated['setoran_bpjs_tk'] = 0;
        }

        if(!$validated['tunjangan_bpjs_tk']){
            $validated['tunjangan_bpjs_tk'] = 0;
        }

        if(!$validated['tunjangan_pensiun']){
            $validated['tunjangan_pensiun'] = 0;
        }

        if(!$validated['tunjangan_komunikasi']){
            $validated['tunjangan_komunikasi'] = 0;
        }

        if(!$validated['tunjangan_pph_21']){
            $validated['tunjangan_pph_21'] = 0;
        }

        if(!$validated['pot_lainnya']){
            $validated['pot_lainnya'] = 0;
        }

        if(!$validated['lembur']){
            $validated['lembur'] = 0;
        }

        $validated['gaji'] = str_replace(',', '', $validated['gaji']);
        $validated['setoran_bpjs_kes'] = str_replace(',', '', $validated['setoran_bpjs_kes']);
        $validated['tunjangan_bpjs_kes'] = str_replace(',', '', $validated['tunjangan_bpjs_kes']);
        $validated['setoran_bpjs_tk'] = str_replace(',', '', $validated['setoran_bpjs_tk']);
        $validated['tunjangan_bpjs_tk'] = str_replace(',', '', $validated['tunjangan_bpjs_tk']);
        $validated['tunjangan_pensiun'] = str_replace(',', '', $validated['tunjangan_pensiun']);
        $validated['tunjangan_komunikasi'] = str_replace(',', '', $validated['tunjangan_komunikasi']);
        $validated['tunjangan_pph_21'] = str_replace(',', '', $validated['tunjangan_pph_21']);
        $validated['pot_lainnya'] = str_replace(',', '', $validated['pot_lainnya']);
        $validated['lembur'] = str_replace(',', '', $validated['lembur']);

        Payroll::create($validated);
        return redirect('/payroll')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function edit($id)
    {
        $dataPayroll = Payroll::query()->find($id);

        $dataUser = User::query()->find($dataPayroll->user_id);
        $dataBpjsEditPayroll = $this->karyawanService->getBpjsEditPayroll($dataPayroll);

        return view('payroll.edit', [
            'title' => 'Edit Data Penggajian',
            'user' => $dataUser,
            'data' => $dataPayroll,
            'data_bpjs_kesehatan' => $dataBpjsEditPayroll['bpjsKesehatan'],
            'data_bpjs_ketenagakerjaan' => $dataBpjsEditPayroll['bpjsKetenagakerjaan'],
            'data_bpjs_ketenagakerjaan_jkk' => $dataBpjsEditPayroll['bpjsKetenagakerjaanJkk']
        ]);
    }
    public function update(Request $request, $id)
    {
        dd($request->all());

        $payroll = Payroll::query()->find($id);
        $validated = $request->validate([
            'user_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'persentase_kehadiran' => 'required',
            'no_gaji' => 'required',
            'gaji_pokok' => 'required',
            'uang_transport' => 'required',
            'jumlah_mangkir' => 'required',
            'uang_mangkir' => 'required',
            'total_mangkir' => 'required',
            'jumlah_lembur' => 'required',
            'uang_lembur' => 'required',
            'total_lembur' => 'required',
            'jumlah_izin' => 'required',
            'uang_izin' => 'required',
            'total_izin' => 'required',
            'jumlah_bonus' => 'required',
            'uang_bonus' => 'required',
            'total_bonus' => 'required',
            'jumlah_terlambat' => 'required',
            'uang_terlambat' => 'required',
            'total_terlambat' => 'required',
            'jumlah_kehadiran' => 'required',
            'uang_kehadiran' => 'required',
            'total_kehadiran' => 'required',
            'saldo_kasbon' => 'required',
            'bayar_kasbon' => 'required',
            'jumlah_thr' => 'required',
            'uang_thr' => 'required',
            'total_thr' => 'required',
            'loss' => 'sometimes',
            'total_penjumlahan' => 'required',
            'total_pengurangan' => 'required',
            'grand_total' => 'required',
        ]);

        $validated['gaji_pokok'] = str_replace(',', '', $validated['gaji_pokok']);
        $validated['uang_transport'] = str_replace(',', '', $validated['uang_transport']);
        $validated['uang_mangkir'] = str_replace(',', '', $validated['uang_mangkir']);
        $validated['total_mangkir'] = str_replace(',', '', $validated['total_mangkir']);
        $validated['uang_lembur'] = str_replace(',', '', $validated['uang_lembur']);
        $validated['total_lembur'] = str_replace(',', '', $validated['total_lembur']);
        $validated['uang_izin'] = str_replace(',', '', $validated['uang_izin']);
        $validated['total_izin'] = str_replace(',', '', $validated['total_izin']);
        $validated['uang_bonus'] = str_replace(',', '', $validated['uang_bonus']);
        $validated['total_bonus'] = str_replace(',', '', $validated['total_bonus']);
        $validated['uang_terlambat'] = str_replace(',', '', $validated['uang_terlambat']);
        $validated['total_terlambat'] = str_replace(',', '', $validated['total_terlambat']);
        $validated['uang_kehadiran'] = str_replace(',', '', $validated['uang_kehadiran']);
        $validated['total_kehadiran'] = str_replace(',', '', $validated['total_kehadiran']);
        $validated['saldo_kasbon'] = str_replace(',', '', $validated['saldo_kasbon']);
        $validated['bayar_kasbon'] = str_replace(',', '', $validated['bayar_kasbon']);
        $validated['uang_thr'] = str_replace(',', '', $validated['uang_thr']);
        $validated['total_thr'] = str_replace(',', '', $validated['total_thr']);
        $validated['loss'] = str_replace(',', '', $validated['loss']);
        $validated['total_penjumlahan'] = str_replace(',', '', $validated['total_penjumlahan']);
        $validated['total_pengurangan'] = str_replace(',', '', $validated['total_pengurangan']);
        $validated['grand_total'] = str_replace(',', '', $validated['grand_total']);

        $user = User::find($request['user_id']);
        $user->update(['saldo_kasbon' => $user->saldo_kasbon + $payroll->bayar_kasbon]);
        $payroll->update($validated);

        $user->update(['saldo_kasbon' => $user->saldo_kasbon - $validated['bayar_kasbon']]);

        return redirect('payroll')->with('success', 'Data Berhasil Diupdate');
    }

    public function delete($id)
    {
        $payroll = Payroll::find($id);
        $user = User::find($payroll->user_id);
        $user->update(['saldo_kasbon' => $user->saldo_kasbon + $payroll->bayar_kasbon]);
        $payroll->delete();
        return redirect('/payroll')->with('success', 'Data Berhasil di Hapus');
    }

    public function download($id)
    {
        $payroll = Payroll::query()->find($id);
        $user = User::query()->find($payroll->user_id);

        $dataKaryawan = $this->karyawanService->getCutiIzinUpahDeduksiKaryawan($user->golongan_id, $user->tipe_karyawan);
        $dataPayroll = Payroll::query()->find($id);
        if($dataKaryawan['bpjsKetenagakerjaan']) {
           $modifiedBpjsKetenagakerjaan = $this->modifyPayrollData($dataKaryawan['bpjsKetenagakerjaan'], collect($dataPayroll));
        }
//        $modifiedBpjsKetenagakerjaanJkk = $this->modifyPayrollData($dataKaryawan['bpjsKetenagakerjaanJkk'], collect($dataPayroll));

        $dataCutiIzin = DataCuti::query()->get();
//        dd($dataCutiIzin);
        $sisa_cuti = $dataPayroll->User->izin_cuti ?? $dataCutiIzin[0]['jumlah'];
//        dd($sisa_cuti);
        $pdf = Pdf::loadView('payroll.download', [
            'title' => 'Penggajian',
            'data_payroll' => $dataPayroll,
            'data_bpjs_kesehatan' => $dataKaryawan['bpjsKesehatan'],
            'data_bpjs_ketenagakerjaan' => $modifiedBpjsKetenagakerjaan ?? $dataKaryawan['bpjsKetenagakerjaan'],
            'data_bpjs_ketenagakerjaan_jkk' => $dataKaryawan['bpjsKetenagakerjaanJkk'],
            'sisa_cuti' => $sisa_cuti,
        ]);

        return $pdf->stream();
    }

//    private function modifyPayrollData($collectionLengkap, $collectionPayroll) {
//        // Pastikan tipe-nya Collection
//        $collectionLengkap = collect($collectionLengkap);
//        $collectionPayroll = collect($collectionPayroll);
//
//        return $collectionLengkap->map(function ($item) use ($collectionPayroll) {
//            // 1. Ambil nama asli dari item collection (misal: "Jaminan Hari Tua")
//            $namaAsli = $item->name;
//            // 2. Buat 'kunci dinamis' yang cocok dengan format array potongan
//            //    ("Jaminan Hari Tua" -> "potongan_Jaminan_Hari_Tua")
//            $kunciDinamis = 'potongan_' . str_replace(' ', '_', $namaAsli);
//            // 3. Cek apakah kunci ini ada di data potongan.
//            //    Jika ada, ambil nilainya. Jika tidak, beri nilai default 0.
//            $nilaiPotongan = $collectionPayroll[$kunciDinamis] ?? 0;
//            // 4. Tambahkan nilai potongan sebagai properti BARU ke dalam item
//            $item->nilai_potongan = $nilaiPotongan;
//            // 5. Kembalikan item yang sudah diperkaya
//            return $item;
//        });
//    }

    private function modifyPayrollData($collectionLengkap, $collectionPayroll)
    {
        // Pastikan tipe-nya Collection
        $collectionLengkap = collect($collectionLengkap);
        $collectionPayroll = collect($collectionPayroll);

        return $collectionLengkap->map(function ($item) use ($collectionPayroll) {
            // Aman untuk array maupun object
            $namaAsli = $item->name ?? $item['name'] ?? null;

            $kunciDinamis = 'potongan_' . str_replace(' ', '_', $namaAsli);
            $nilaiPotongan = $collectionPayroll[$kunciDinamis] ?? 0;

            // Tambahkan properti baru
            if (is_array($item)) {
                $item['nilai_potongan'] = $nilaiPotongan;
            } else {
                $item->nilai_potongan = $nilaiPotongan;
            }

            return $item;
        });
    }

}
