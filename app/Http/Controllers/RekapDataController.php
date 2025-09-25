<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\DynamicUpah;
use App\Models\User;
use App\Models\Lembur;
use App\Models\Counter;
use App\Models\Payroll;
use App\Exports\RekapExport;
use App\Models\MappingShift;
use App\Services\KaryawanService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class RekapDataController extends Controller
{
    protected $karyawanService;

    public function __construct(KaryawanService $karyawanService)
    {
        $this->middleware('auth');
        $this->karyawanService = $karyawanService;
    }

    public function index()
    {
        return view('rekapdata.index', [
            'title' => 'Rekap Data Absensi',
        ]);
    }

    public function getData(Request $request)
    {
        $request->validate([
            'mulai' => 'required',
            'akhir' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');

        $user = User::orderBy('name', 'ASC')->paginate(10)->withQueryString();

        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');
        $title = "Rekap Data Absensi";

        return view('rekapdata.getdata', [
        'title' => $title,
            'data_user' => $user,
            'tanggal_mulai' => $mulai,
            'tanggal_akhir' => $akhir
        ]);
    }

    public function export()
    {
        return (new RekapExport($_GET))->download('List Rekap Data.xlsx');
    }

    private function prosesPotonganJaminan($collection, $gajiPokok)
    {
        // Pengaman: jika datanya bukan collection atau kosong, langsung kembalikan apa adanya.
        if (!$collection instanceof \Illuminate\Support\Collection || $collection->isEmpty()) {
            return $collection;
        }

        return $collection->map(function ($item) use ($gajiPokok) {
            $persentase = (float) str_replace(',', '.', $item->nominal) / 100;
            $nilaiPotongan = $gajiPokok * $persentase;

            $item->nilai_potongan = $nilaiPotongan;
            $item->gaji_nett_setelah_potongan = $gajiPokok - $nilaiPotongan;

            return $item;
        });
    }

    public function payroll(Request $request, $id)
    {
        $user = User::find($id);
        $mulai = $request->input('mulai');
        $akhir = $request->input('akhir');
        $counter = Counter::where('name', 'Gaji')->first();
        $counter->update(['counter' => $counter->counter + 1]);
        $next_number = str_pad($counter->counter, 6, '0', STR_PAD_LEFT);
        $no_gaji = $counter->text . $next_number;
        $dataKaryawan = $this->karyawanService->getCutiIzinUpahDeduksiKaryawan($user->golongan_id, $user->tipe_karyawan);

        // Proses Data Bagian Info Umum
        $pecah_tanggal = explode("-", $mulai);
        $tahun_filter = $pecah_tanggal[0];
        $bulan_filter = $pecah_tanggal[1];
        $jumlah_hadir = $user->MappingShift->whereBetween('tanggal', [$mulai, $akhir])->where('status_absen', '=', 'Masuk')->count();
        $jumlah_izin_telat = $user->MappingShift->whereBetween('tanggal', [$mulai, $akhir])->where('status_absen', 'Izin Telat')->count();
        $jumlah_izin_pulang_cepat = $user->MappingShift->whereBetween('tanggal', [$mulai, $akhir])->where('status_absen', 'Izin Pulang Cepat')->count();
        $libur = $user->MappingShift->whereBetween('tanggal', [$mulai, $akhir])->where('status_absen', 'Libur')->count();
        $timestamp_mulai = strtotime($mulai);
        $timestamp_akhir = strtotime($akhir);
        $selisih_timestamp = $timestamp_akhir - $timestamp_mulai;
        $jumlah_hari = (floor($selisih_timestamp / (60 * 60 * 24)))+1;
        $presentase_kehadiran = (($jumlah_hadir + $jumlah_izin_telat + $jumlah_izin_pulang_cepat + $libur) / $jumlah_hari) * 100;

        // Proses Data Bagian Pendapatan
        if ($presentase_kehadiran == 100) {
            $jumlah_kehadiran = 1;
        } else {
            $jumlah_kehadiran = 0;
        }
        $total_lembur = $user->Lembur->where('status', 'Approved')->whereBetween('tanggal', [$mulai, $akhir])->sum('total_lembur');
        $jam_lembur   = floor($total_lembur / (60 * 60));
        $total_oncall = $user->Oncall->where('status', 'Approved')->whereBetween('tanggal', [$mulai, $akhir])->sum('total_oncall');
        $jam_oncall   = floor($total_oncall / (60 * 60));

        // Proses Data Bagian Potongan
        $jumlah_izin = $user->Cuti->whereBetween('tanggal', [$mulai, $akhir])->where('nama_cuti', 'Izin Masuk')->where('status_cuti', 'Diterima')->count();
        $jumlah_terlambat = $user->MappingShift->whereBetween('tanggal', [$mulai, $akhir])->where('telat', '>', 0)->count();
        $jumlah_mangkir = $user->MappingShift->whereBetween('tanggal', [$mulai, $akhir])->where('status_absen', 'Tidak Masuk')->count();
        $gajiPokok = $dataKaryawan['dynamicUpah'];

        if ($dataKaryawan['bpjsKetenagakerjaan'] !== null) {
            $dataKaryawan['bpjsKetenagakerjaan'] = $this->prosesPotonganJaminan(
                $dataKaryawan['bpjsKetenagakerjaan'],
                $gajiPokok[0]['jumlah']
            );
        }

        if($dataKaryawan['bpjsKetenagakerjaanJkk'] !== null) {
            $dataKaryawan['bpjsKetenagakerjaanJkk'] = $this->prosesPotonganJaminan(
                $dataKaryawan['bpjsKetenagakerjaanJkk'],
                $gajiPokok[0]['jumlah']
            );
        }

        return view('rekapdata.payroll', [
            'title' => 'Penggajian',
            'user' => $user,
            'tanggal_mulai' => $mulai,
            'tanggal_akhir' => $akhir,
            'tahun_filter' => $tahun_filter,
            'bulan_filter' => $bulan_filter,
            'persentase_kehadiran' => $presentase_kehadiran,
            'jumlah_kehadiran' => $jumlah_kehadiran,
            'jumlah_terlambat' => $jumlah_terlambat,
            'jam_lembur' => $jam_lembur,
            'jam_oncall' => $jam_oncall,
            'jumlah_izin' => $jumlah_izin,
            'jumlah_mangkir' => $jumlah_mangkir,
            'no_gaji' => $no_gaji,
            'data_cuti_izin' => $dataKaryawan['cuti_izin'],
            'data_dynamic_upah' => $dataKaryawan['dynamicUpah'],
            'data_deduksi' => $dataKaryawan['deduksi'],
            'data_bpjs_kesehatan' => $dataKaryawan['bpjsKesehatan'],
            'data_bpjs_ketenagakerjaan' => $dataKaryawan['bpjsKetenagakerjaan'],
            'data_bpjs_ketenagakerjaan_jkk' => $dataKaryawan['bpjsKetenagakerjaanJkk'],
        ]);
    }

    public function tambahPayroll(Request $request)
    {
        $cek = Payroll::query()->where('user_id', $request['user_id'])->where('bulan', $request['bulan'])->where('tahun', $request['tahun'])->first();
        if ($cek) {
            Alert::error('Failed', 'Sudah Ada Data Pada Bulan Dan Tahun Tersebut!');
            return redirect('/rekap-data/get-data?mulai='.$request['mulai'].'&akhir='.$request['akhir'])->with('failed', 'Data Berhasil Disimpan');
        }

//        try {
            $validated = $request->validate([
                'user_id' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'persentase_kehadiran' => 'required',
                'no_gaji' => 'required',
                'gaji_pokok' => 'required',
                'jumlah_kehadiran' => 'required',
                'uang_kehadiran' => 'required',
                'total_kehadiran' => 'required',
                'jumlah_lembur' => 'required',
                'uang_lembur' => 'required',
                'total_lembur' => 'required',
                'jumlah_oncall' => 'required',
                'uang_oncall' => 'required',
                'total_oncall' => 'required',
    //            'saldo_kasbon' => 'required',
    //            'bayar_kasbon' => 'required',
                'jumlah_izin' => 'required',
                'uang_izin' => 'required',
                'total_izin' => 'required',
                'jumlah_terlambat' => 'required',
                'uang_terlambat' => 'required',
                'total_terlambat' => 'required',
                'jumlah_mangkir' => 'required',
                'uang_mangkir' => 'required',
                'total_mangkir' => 'required',
//                'potongan_bpjs_kesehatan' => 'required',
//                'potongan_Jaminan_Hari_Tua' => 'required',
//                'potongan_Jaminan_Pensiun' => 'required',
//                'potongan_Jaminan_Kematian' => 'required',
//                'potongan_Jaminan_Kehilangan_Pekerjaan' => 'required',
//                'potongan_Jaminan_Kecelakaan_Kerja' => 'required',
                'uang_makan' => 'required',
                'uang_transport' => 'required',
                'jumlah_bonus' => 'required',
                'uang_bonus' => 'required',
                'total_bonus' => 'required',
                'jumlah_thr' => 'required',
                'uang_thr' => 'required',
                'total_thr' => 'required',
    //            'loss' => 'required',
                'total_penjumlahan' => 'required',
                'total_pengurangan' => 'required',
                'grand_total' => 'required',
            ]);

//        } catch (\Exception $e) {
//            Alert::error('Failed', 'Data Gagal Disimpan!');
//            Log::info($e);
//            return redirect('/rekap-data/get-data?mulai='.$request['mulai'].'&akhir='.$request['akhir'])->with('failed', 'Data Berhasil Disimpan');
//        }

        $validated['user_id'] = (int) str_replace(',', '', $validated['user_id']);
        $validated['bulan'] = (int) str_replace(',', '', $validated['bulan']);
        $validated['tahun'] = (int) str_replace(',', '', $validated['tahun']);
//        $validated['no_gaji'] = str_replace(',', '', $validated['no_gaji']);
        $validated['persentase_kehadiran'] = (int) (str_replace(',', '', $validated['persentase_kehadiran']) ?? 0);
        $validated['gaji_pokok'] = (int) (str_replace(',', '', $validated['gaji_pokok']) ?? '0');
        $validated['jumlah_kehadiran'] = (int) (str_replace(',', '', $validated['jumlah_kehadiran']) ?? '0');
        $validated['uang_kehadiran'] = (int) (str_replace(',', '', $validated['uang_kehadiran']) ?? '0');
        $validated['total_kehadiran'] = (int) (str_replace(',', '', $validated['total_kehadiran']) ?? '0');
        $validated['jumlah_lembur'] = (int) (str_replace(',', '', $validated['jumlah_lembur']) ?? '0');
        $validated['uang_lembur'] = (int) (str_replace(',', '', $validated['uang_lembur']) ?? '0');
        $validated['total_lembur'] = (int) (str_replace(',', '', $validated['total_lembur']) ?? '0');
        $validated['jumlah_oncall'] = (int) (str_replace(',', '', $validated['jumlah_oncall']) ?? '0');
        $validated['uang_oncall'] = (int) (str_replace(',', '', $validated['uang_oncall']) ?? '0');
        $validated['total_oncall'] = (int) (str_replace(',', '', $validated['total_oncall']) ?? '0');
//        $validated['saldo_kasbon'] = '0';
//        $validated['bayar_kasbon'] = '0';
        $validated['jumlah_izin'] = (int) (str_replace(',', '', $validated['jumlah_izin']) ?? '0');
        $validated['uang_izin'] = (int) (str_replace(',', '', $validated['uang_izin']) ?? '0');
        $validated['total_izin'] = (int) (str_replace(',', '', $validated['total_izin']) ?? '0');
        $validated['jumlah_terlambat'] = (int) (str_replace(',', '', $validated['jumlah_izin']) ?? '0');
        $validated['uang_terlambat'] = (int) (str_replace(',', '', $validated['uang_terlambat']) ?? '0');
        $validated['total_terlambat'] = (int) (str_replace(',', '', $validated['total_terlambat']) ?? '0');
        $validated['jumlah_mangkir'] = (int) (str_replace(',', '', $validated['jumlah_izin']) ?? '0');
        $validated['uang_mangkir'] = (int) (str_replace(',', '', $validated['uang_mangkir']) ?? '0');
        $validated['total_mangkir'] = (int) (str_replace(',', '', $validated['total_mangkir']) ?? '0');
        $validated['potongan_bpjs_kesehatan'] = $request['potongan_bpjs_kesehatan'] ? (int) str_replace(',', '', $request['potongan_bpjs_kesehatan']) : 0;
        $validated['potongan_Jaminan_Hari_Tua'] = $request['potongan_Jaminan_Hari_Tua'] ? (int) str_replace(',', '', $request['potongan_Jaminan_Hari_Tua']) : 0;
        $validated['potongan_Jaminan_Pensiun'] = $request['potongan_Jaminan_Pensiun'] ? (int) str_replace(',', '', $request['potongan_Jaminan_Pensiun']) : 0;
        $validated['potongan_Jaminan_Kematian'] = $request['potongan_Jaminan_Kematian'] ? (int) str_replace(',', '', $request['potongan_Jaminan_Kematian']) : 0;
        $validated['potongan_Jaminan_Kehilangan_Pekerjaan'] = $request['potongan_Jaminan_Kehilangan_Pekerjaan'] ? (int) str_replace(',', '', $request['potongan_Jaminan_Kehilangan_Pekerjaan']) : 0;
        $validated['potongan_Jaminan_Kecelakaan_Kerja'] = $request['potongan_Jaminan_Kecelakaan_Kerja'] ? (int) str_replace(',', '', $request['potongan_Jaminan_Kecelakaan_Kerja']) : 0;
        $validated['uang_makan'] = (int) (str_replace(',', '', $validated['uang_makan']) ?? '0');
        $validated['uang_transport'] = (int) (str_replace(',', '', $validated['uang_transport']) ?? '0');
        $validated['jumlah_bonus'] = (int) (str_replace(',', '', $validated['jumlah_bonus']) ?? '0');
        $validated['uang_bonus'] = (int) (str_replace(',', '', $validated['uang_bonus']) ?? '0');
        $validated['total_bonus'] = (int) (str_replace(',', '', $validated['total_bonus']) ?? '0');
        $validated['jumlah_thr'] = (int) (str_replace(',', '', $validated['jumlah_thr']) ?? '0');
        $validated['uang_thr'] = (int) (str_replace(',', '', $validated['uang_thr']) ?? '0');
        $validated['total_thr'] = (int) (str_replace(',', '', $validated['total_thr']) ?? '0');
//        $validated['loss'] = '0';
        $validated['total_penjumlahan'] = (int) (str_replace(',', '', $validated['total_penjumlahan']) ?? '0');
        $validated['total_pengurangan'] = (int) (str_replace(',', '', $validated['total_pengurangan']) ?? '0');
        $validated['grand_total'] = (int) (str_replace(',', '', $validated['grand_total']) ?? '0');


//        $user = User::find($request['user_id']);
//        $user->update(['saldo_kasbon' => $user->saldo_kasbon - $validated['bayar_kasbon']]);

        try {
            Payroll::create($validated);
        } catch (\Exception $e) {
            Alert::error('Failed', 'Data Gagal Disimpan!');
            Log::error($e->getMessage());
            return redirect('/rekap-data/get-data?mulai='.$request['mulai'].'&akhir='.$request['akhir'])
                ->with('failed', 'Data Gagal Disimpan',);
        }

        return redirect('/rekap-data/get-data?mulai='.$request['mulai'].'&akhir='.$request['akhir'])
            ->with('success', 'Data Berhasil Disimpan',);
    }

    public function detailPdf()
    {
        $pdf = Pdf::loadView('rekapdata.detailPdf', [
            'title' => 'Detail PDF',
            'data' => MappingShift::dataAbsen()->get()
        ]);

        return $pdf->stream();
    }

    public function rekapPdf()
    {
        $pdf = Pdf::loadView('rekapdata.rekapPdf', [
            'title' => 'Rekap PDF',
            'data' => User::orderBy('name', 'ASC')->get()
        ]);

        return $pdf->stream();
    }
}
