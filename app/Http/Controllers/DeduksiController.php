<?php

namespace App\Http\Controllers;

use App\Models\Deduksi;
use Illuminate\Http\Request;

class DeduksiController extends Controller
{
    public function index()
    {
        return view('deduksi.index', [
            'title' => 'Data Pengurangan Gaji',
            'data' => Deduksi::all(),
        ]);
    }

    public function tambahDeduksi()
    {
        return view('deduksi.tambah', [
            'title' => 'Tambah Data Pengurangan Gaji',
        ]);
    }

    public function prosesTambahDeduksi(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
        ]);

        Deduksi::create($validatedData);

        return redirect('/deduksi')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function editDeduksi( $id)
    {
        $deduksi = Deduksi::find($id);

        return view('deduksi.edit', [
            'title' => 'Edit Pengurangan Gaji',
            'data_deduksi' => $deduksi
        ]);
    }

    public function prosesEditDeduksi(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
        ]);

        Deduksi::where('id', $id)->update($validatedData);

        return redirect('/deduksi')->with('success', 'Data Berhasil di Update');
    }

    public function deleteDeduksi($id)
    {
        $delete = Deduksi::find($id);

        $delete->delete();

        return redirect('/deduksi')->with('success', 'Data Berhasil di Hapus');
    }
}
