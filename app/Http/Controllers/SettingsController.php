<?php

namespace App\Http\Controllers;

use App\Models\Ketenagakerjaan;
use App\Models\KetenagakerjaanJkk;
use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $title = 'Settings';
        $data = settings::first();
        $data_ketenagakerjaan = Ketenagakerjaan::all();
        $data_ketenagakerjaan_jkk = KetenagakerjaanJkk::all();
        return view('settings.index', compact(
            'title',
            'data',
            'data_ketenagakerjaan',
            'data_ketenagakerjaan_jkk'
        ));
    }

    public function store(Request $request)
    {
        $settings = settings::first();

        $validated = $request->validate([
            'name' => 'required',
            'logo' => 'image|file|max:10240|nullable',
            'ttd_gaji' => 'image|file|max:10240|nullable',
            'ttd_cuti' => 'image|file|max:10240|nullable',
            'alamat' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
            'bpjs_kesehatan' => 'nullable',
            'bpjs_kesehatan_kontrak' => 'nullable',
            'bpjs_ketenagakerjaan' => 'nullable',
            'bpjs_ketenagakerjaan_jht' => 'nullable',
            'bpjs_ketenagakerjaan_jp' => 'nullable',
            'bpjs_ketenagakerjaan_jkp' => 'nullable',
            'bpjs_ketenagakerjaan_jkm' => 'nullable',
            'bpjs_ketenagakerjaan_jkk' => 'nullable',
        ]);

        $fields = [
            'bpjs_ketenagakerjaan_jht',
            'bpjs_ketenagakerjaan_jp',
            'bpjs_ketenagakerjaan_jkp',
            'bpjs_ketenagakerjaan_jkm'
        ];

//        foreach ($fields as $field) {
//            if (array_key_exists($field, $validated)) {
//                $validated[$field] = $request->input($field);
//            } else {
//                $validated[$field] = null;
//            }
//        }

        if ($request->bpjs_ketenagakerjaan === 'ya' &&
            $request->bpjs_ketenagakerjaan_jht === null &&
            $request->bpjs_ketenagakerjaan_jp === null &&
            $request->bpjs_ketenagakerjaan_jkp === null &&
            $request->bpjs_ketenagakerjaan_jkm === null)
        {
            $validated['bpjs_ketenagakerjaan'] = 'tidak';
        }

        if ($request->bpjs_ketenagakerjaan === 'tidak') {
            $validated['bpjs_ketenagakerjaan_jht'] = null;
            $validated['bpjs_ketenagakerjaan_jp'] = null;
            $validated['bpjs_ketenagakerjaan_jkp'] = null;
            $validated['bpjs_ketenagakerjaan_jkm'] = null;
            $validated['bpjs_ketenagakerjaan_jkk'] = null;
        }

        if (is_null($request->jkkCheckbox)) {
            $validated['bpjs_ketenagakerjaan_jkk'] = null;
        }
        if ($request->file('logo')) {
            $validated['logo'] = $request->file('logo')->store('logo','public');
        }
        if ($request->file('ttd_gaji')) {
            $validated['ttd_gaji'] = $request->file('ttd_gaji')->store('ttd_gaji','public');
        }
        if ($request->file('ttd_cuti')) {
            $validated['ttd_cuti'] = $request->file('ttd_cuti')->store('ttd_cuti','public');
        }

        Log::info($request);
        foreach ($validated as $key => $value) {
            Log::info($key.'='.$value);
        }

        $settings->update($validated);
        return back()->with('success', 'Data Berhasil Ditambahkan');
    }
}
