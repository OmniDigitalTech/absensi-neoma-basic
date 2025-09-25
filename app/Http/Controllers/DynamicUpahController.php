<?php

namespace App\Http\Controllers;

use App\Models\DynamicUpah;
use App\Models\Golongan;
use Illuminate\Http\Request;

class DynamicUpahController extends Controller
{
    public function index()
    {
        $data  = collect();

        DynamicUpah::with('Golongan')->orderBy('golongan_id', 'ASC')->chunkById(1000, function ($collection) use (&$data) {
            $data->push(...$collection);
        });

        return view('dynamicUpah.index', [
            'title' => 'Data Upah Golongan',
            'data' => $data
        ]);
    }

    public function tambah()
    {
        return view('dynamicUpah.tambah', [
            'title' => 'Tambah Data Dynamic Upah',
            'data_golongan' => Golongan::orderBy('name', 'ASC')->get(),
        ]);
    }

    public function tambahProses(Request $request)
    {
        $request['jumlah'] = str_replace(',', '', $request['jumlah']);
        $validated = $request->validate([
            'golongan_id' => 'required',
            'nama' => 'required',
            'jumlah' => 'required',
            'keterangan' => 'required',
        ]);

        DynamicUpah::create($validated);
        return redirect('/dynamic-upah')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        return view('dynamicUpah.edit', [
            'title' => 'Edit Data Dynamic Upah',
            'data' => DynamicUpah::find($id),
            'data_golongan' => Golongan::orderBy('name', 'ASC')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request['jumlah'] = str_replace(',', '', $request['jumlah']);
        $validated = $request->validate([
            'golongan_id' => 'required',
            'nama' => 'required',
            'jumlah' => 'required',
            'keterangan' => 'required',
        ]);

        DynamicUpah::where('id', $id)->update($validated);
        return redirect('/dynamic-upah')->with('success', 'Data Berhasil Diupdate');
    }

    public function delete($id)
    {
        DynamicUpah::where('id', $id)->delete();
        return redirect('/dynamic-upah')->with('success', 'Data Berhasil Didelete');
    }
}
