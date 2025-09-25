<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Tunjangan;
use App\Models\Upah;
use Illuminate\Http\Request;

class UpahController extends Controller
{
    public function index()
    {
        return view('upah.index', [
            'title' => 'Data Upah Golongan',
            'data' => Upah::all(),
        ]);
    }

    public function tambah()
    {
        return view('upah.tambah', [
            'title' => 'Tambah Data Upah',
            'data_golongan' => Golongan::orderBy('name', 'ASC')->get(),
        ]);
    }

    public function tambahProses(Request $request)
    {
        $request['gaji_pokok'] = str_replace(',', '', $request['gaji_pokok']);
        $request['kehadiran'] = str_replace(',', '', $request['kehadiran']);
        $request['lembur'] = str_replace(',', '', $request['lembur']);
        $request['oncall'] = str_replace(',', '', $request['oncall']);
        $validated = $request->validate([
            'golongan_id' => 'required',
            'gaji_pokok' => 'required',
            'kehadiran' => 'required',
            'lembur' => 'required',
            'oncall' => 'required',
        ]);

        Upah::create($validated);
        return redirect('/upah')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        return view('upah.edit', [
            'title' => 'Edit Data Upah',
            'data' => Upah::find($id),
            'data_golongan' => Golongan::orderBy('name', 'ASC')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request['gaji_pokok'] = str_replace(',', '', $request['gaji_pokok']);
        $request['kehadiran'] = str_replace(',', '', $request['kehadiran']);
        $request['lembur'] = str_replace(',', '', $request['lembur']);
        $request['oncall'] = str_replace(',', '', $request['oncall']);
        $validated = $request->validate([
            'golongan_id' => 'required',
            'gaji_pokok' => 'required',
            'kehadiran' => 'required',
            'lembur' => 'required',
            'oncall' => 'required',
        ]);

        Upah::where('id', $id)->update($validated);
        return redirect('/upah')->with('success', 'Data Berhasil Diupdate');
    }

    public function delete($id)
    {
        Upah::where('id', $id)->delete();
        return redirect('/upah')->with('success', 'Data Berhasil Didelete');
    }
}
