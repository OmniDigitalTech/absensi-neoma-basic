<?php

namespace App\Http\Controllers;

use App\Models\DataCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataCutiController extends Controller
{
    public function index()
    {
        return view('cuti.indexCutiIzin', [
            'title' => 'Atur Data Jatah Cuti & Izin',
            'data' => DataCuti::all(),
        ]);
    }

    public function tambahCutiIzin(Request $request)
    {
        return view('cuti.tambahCutiIzin', [
            'title' => 'Tambah Cuti & Izin',
        ]);
    }

    public function tambahDataCutiIzinProses(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'jumlah' => 'required',
        ]);

        DataCuti::create($validatedData);

        return redirect('/data-cuti/cuti-izin')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function editCutiIzin($id)
    {
        $dataCuti = DataCuti::find($id);

        return view('cuti.editCutiIzin', [
            'title' => 'Edit Cuti & Izin',
            'data_cuti_izin' => $dataCuti
        ]);
    }

    public function editCutiIzinProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'jumlah' => 'required',
        ]);

        DataCuti::where('id', $id)->update($validatedData);

        return redirect('/data-cuti/cuti-izin')->with('success', 'Data Berhasil di Update');
    }

    public function deleteCutiIzin($id)
    {
        $delete = DataCuti::find($id);

        $delete->delete();

        return redirect('/data-cuti/cuti-izin')->with('success', 'Data Berhasil di Hapus');
    }
}
