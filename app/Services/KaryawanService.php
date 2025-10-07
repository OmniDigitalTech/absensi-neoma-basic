<?php

namespace App\Services;

use App\Models\DataCuti;
use App\Models\Deduksi;
use App\Models\DynamicUpah;
use App\Models\Kesehatan;
use App\Models\Ketenagakerjaan;
use App\Models\KetenagakerjaanJkk;
use App\Models\settings;
use App\Models\Upah;

class KaryawanService
{
    public function getCutiIzinUpahDeduksiKaryawan ($golonganId, $tipeKaryawan) {
        $settings = settings::query()->first();
        $cutiIzin = [];
        $upah = [];
//        $dynamicUpah = [];
        $deduksi = [];
        $bpjsKesehatan = [];
        $bpjsKetenagakerjaan = [];
        $bpjsKetenagakerjaanJkk = [];
        $dataIdBpjsKetenagakerjaan = [];
        $fieldsbpjsKetenagakerjaan = [
            'bpjs_ketenagakerjaan_jht',
            'bpjs_ketenagakerjaan_jp',
            'bpjs_ketenagakerjaan_jkp',
            'bpjs_ketenagakerjaan_jkm'
        ];


        if ($golonganId) {
            $cutiIzin = DataCuti::all();
            $upah = Upah::query()->where('golongan_id', $golonganId)->get();
//            $dynamicUpah = DynamicUpah::where('golongan_id', $golonganId)->get();
            $deduksi = Deduksi::all();

            if ($upah->isNotEmpty()) {
                if ($settings->bpjs_kesehatan === 'ya') {
                    if ($tipeKaryawan === 'tetap' || ($tipeKaryawan === 'kontrak' && $settings->bpjs_kesehatan_kontrak === 'ya')) {
                        $gajiPokok = $upah[0]->gaji_pokok;
                        if ($gajiPokok > 4000000) {
//                            $bpjsKesehatan = Kesehatan::query()->where('id', 1)->get();
                            $bpjsKesehatanRaw = Kesehatan::query()->where('id', 1)->get();

                            $bpjsKesehatan = $this->modifyCollectionData($bpjsKesehatanRaw);
                        } else {
//                            $bpjsKesehatan = Kesehatan::query()->where('id', 2)->get();
                            $bpjsKesehatanRaw = Kesehatan::query()->where('id', 2)->get();

                            $bpjsKesehatan = $this->modifyCollectionData($bpjsKesehatanRaw);
                        }
                    }
                }

                if ($settings->bpjs_ketenagakerjaan === 'ya') {
                    foreach ($fieldsbpjsKetenagakerjaan as $field) {
                        if ($settings->$field !== null) {
                            $dataIdBpjsKetenagakerjaan[] = $settings->$field;
                        }
                    }
                    if (!empty($dataIdBpjsKetenagakerjaan)) {
                        $bpjsKetenagakerjaan = Ketenagakerjaan::query()->whereIn('id', $dataIdBpjsKetenagakerjaan)->get();
                    }
                    $bpjsKetenagakerjaanJkk = KetenagakerjaanJkk::query()->where('id', $settings->bpjs_ketenagakerjaan_jkk)->get();
                }
            }
        }

        return [
            'cuti_izin' => $cutiIzin,
            'upah' => $upah,
//            'dynamicUpah' => $dynamicUpah,
            'deduksi' => $deduksi,
            'bpjsKesehatan' => $bpjsKesehatan,
            'bpjsKetenagakerjaan' => $bpjsKetenagakerjaan,
            'bpjsKetenagakerjaanJkk' => $bpjsKetenagakerjaanJkk
        ];
    }

    private function modifyCollectionData($collection) {
        return $collection->map(function ($item) {
            $oldData = $item->name;
            $NewData = str_replace('Bpjs', 'BPJS', ucwords($oldData));
            $item->name = $NewData;

            $addNewData = implode(' ', array_slice(explode(' ', $NewData), -1));
            $item->kelas = $addNewData;

            return $item;
        });
    }
}
