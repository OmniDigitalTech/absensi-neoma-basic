<?php

namespace App\Http\Controllers;

use App\Models\Kesehatan;
use App\Models\Ketenagakerjaan;
use App\Models\KetenagakerjaanJkk;
use Illuminate\Http\Request;

class BpjsController extends Controller
{
    public function index()
    {
        return view('bpjs.index', [
            'title' => 'Data BPJS Kesehatan & Ketenagakerjaan',
            'data_kesehatan' => Kesehatan::all(),
            'data_ketenagakerjaan' => Ketenagakerjaan::all(),
            'data_ketenagakerjaan_jkk' => KetenagakerjaanJkk::all(),
        ]);
    }

    public function tambahKetenagakerjaan()
    {
        return view('bpjs.tambah', [
            'title' => 'Tambah Data Pengurangan Gaji',
        ]);
    }

    public function prosesTambahKetenagakerjaan(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'nominal' => 'required',
        ]);

        Ketenagakerjaan::create($validatedData);

        return redirect('/bpjs')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function editKetenagakerjaan( $id)
    {
        $ketenagakerjaan = Ketenagakerjaan::find($id);

        return view('bpjs.edit', [
            'title' => 'Edit Bpjs Ketenagakerjaan',
            'data_ketenagakerjaan' => $ketenagakerjaan
        ]);
    }

    public function prosesEditKetenagakerjaan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'nominal' => 'required',
        ]);

        Ketenagakerjaan::where('id', $id)->update($validatedData);

        return redirect('/bpjs')->with('success', 'Data Berhasil di Update');
    }

    public function deleteKetenagakerjaan($id)
    {
        $delete = Ketenagakerjaan::find($id);

        $delete->delete();

        return redirect('/bpjs')->with('success', 'Data Berhasil di Hapus');
    }
}
