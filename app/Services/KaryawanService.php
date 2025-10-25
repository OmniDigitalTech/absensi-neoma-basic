<?php

namespace App\Services;

use App\Models\Cuti;
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
    /**
     * Memeriksa ketersediaan jadwal cuti untuk seorang user.
     *
     * @param int $userId
     * @param string $tglMulai
     * @param string $tglAkhir
     * @return bool - true jika tersedia, false jika sudah ada
     */
    public function isCutiIzinScheduleAvailable(int $userId, string $tglMulai, string $tglAkhir): bool
    {
        $isOverlap = Cuti::query()
            ->where('user_id', $userId)
            ->where('tanggal_mulai', '<=', $tglAkhir)
            ->where('tanggal_akhir', '>=', $tglMulai)
            ->where(function ($query) {
                $query->where('approval1', '!=', 'ditolak')
                    ->orWhereNull('approval1');
            })
            ->where(function ($query) {
                $query->where('approval2', '!=', 'ditolak')
                    ->orWhereNull('approval2');
            })
            ->where(function ($query) {
                $query->where('approval3', '!=', 'ditolak')
                    ->orWhereNull('approval3');
            })
            ->exists();

        // Jika data tumpang tindih DITEMUKAN (true), maka jadwal TIDAK TERSEDIA (return false)
        return !$isOverlap;
    }

    public function getCutiIzinUpahDeduksiKaryawan ($golonganId, $tipeKaryawan): array
    {
        $settings = settings::query()->first();
        $cutiIzin = [];
        $upah = [];
//        $dynamicUpah = [];
        $deduksi = [];
        $bpjsKesehatan = [];
        $getBpjsKetenagakerjaan = [];
//        $bpjsKetenagakerjaan = [];
//        $bpjsKetenagakerjaanJkk = [];
//        $dataIdBpjsKetenagakerjaan = [];
//        $fieldsBpjsKetenagakerjaan = [
//            'bpjs_ketenagakerjaan_jht',
//            'bpjs_ketenagakerjaan_jp',
//            'bpjs_ketenagakerjaan_jkp',
//            'bpjs_ketenagakerjaan_jkm'
//        ];


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

                $getBpjsKetenagakerjaan = $this->getActiveSettingBpjsKetenagakerjaan();
//                if ($settings->bpjs_ketenagakerjaan === 'ya') {
//                    foreach ($fieldsBpjsKetenagakerjaan as $field) {
//                        if ($settings->$field !== null) {
//                            $dataIdBpjsKetenagakerjaan[] = $settings->$field;
//                        }
//                    }
//                    if (!empty($dataIdBpjsKetenagakerjaan)) {
//                        $bpjsKetenagakerjaan = Ketenagakerjaan::query()->whereIn('id', $dataIdBpjsKetenagakerjaan)->get();
//                    }
//                    $bpjsKetenagakerjaanJkk = KetenagakerjaanJkk::query()->where('id', $settings->bpjs_ketenagakerjaan_jkk)->get();
//                }
            }
        }

        return [
            'cuti_izin' => $cutiIzin,
            'upah' => $upah,
//            'dynamicUpah' => $dynamicUpah,
            'deduksi' => $deduksi,
            'bpjsKesehatan' => $bpjsKesehatan,
            'bpjsKetenagakerjaan' => $getBpjsKetenagakerjaan['bpjsKetenagakerjaan'] ?? [],
            'bpjsKetenagakerjaanJkk' => $getBpjsKetenagakerjaan['bpjsKetenagakerjaanJkk'] ?? []
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

    public function getBpjsEditPayroll($dataPayroll): array
    {
//        $settings = settings::query()->first();
        $bpjsKesehatan = [];
        $bpjsKetenagakerjaan = [];
        $bpjsKetenagakerjaanDenganPotongan = [];
        $bpjsKetenagakerjaanJkk = [];
        $bpjsKetenagakerjaanJkkDenganPotongan = [];
        $fieldsIdBpjsKetenagakerjaan = [
            'id_Jaminan_Hari_Tua',
            'id_Jaminan_Pensiun',
            'id_Jaminan_Kematian',
            'id_Jaminan_Kehilangan_Pekerjaan'
        ];

        if ($dataPayroll) {
            if ($dataPayroll->potongan_bpjs_kesehatan !== 0) {
                $gajiPokok = $dataPayroll->gaji_pokok;
                if ($gajiPokok > 4000000) {
                    $bpjsKesehatanRaw = Kesehatan::query()->where('id', 1)->get();

                    $bpjsKesehatan = $this->modifyCollectionData($bpjsKesehatanRaw);
                } else {
                    $bpjsKesehatanRaw = Kesehatan::query()->where('id', 2)->get();

                    $bpjsKesehatan = $this->modifyCollectionData($bpjsKesehatanRaw);
                }
            }

            foreach ($fieldsIdBpjsKetenagakerjaan as $field) {
                if ($dataPayroll->$field !== 0) {
                    $dataIdBpjsKetenagakerjaan[] = $dataPayroll->$field;
                }
            }
            if (!empty($dataIdBpjsKetenagakerjaan)) {
                $bpjsKetenagakerjaan = Ketenagakerjaan::query()->whereIn('id', $dataIdBpjsKetenagakerjaan)->get();

                $bpjsKetenagakerjaanDenganPotongan = $bpjsKetenagakerjaan->map(function ($item) use ($dataPayroll) {
                    $namaKomponen = $item->name;
                    $kunciPotongan = 'potongan_' . str_replace(' ', '_', $namaKomponen);
                    $nilaiPotongan = $dataPayroll->$kunciPotongan ?? 0;
                    $item->nilai_potongan = $nilaiPotongan;
                    return $item;
                });
            }
            if ($dataPayroll->id_Jaminan_Kecelakaan_Kerja !== 0) {
               $bpjsKetenagakerjaanJkk = KetenagakerjaanJkk::query()->where('id', $dataPayroll->id_Jaminan_Kecelakaan_Kerja)->get();

                $bpjsKetenagakerjaanJkkDenganPotongan = $bpjsKetenagakerjaanJkk->map(function ($item) use ($dataPayroll) {
                    $nilaiPotongan = $dataPayroll->potongan_Jaminan_Kecelakaan_Kerja ?? 0;
                    $item->nilai_potongan = $nilaiPotongan;
                    return $item;
                });
            }
        }

        return [
            'bpjsKesehatan' => $bpjsKesehatan,
            'bpjsKetenagakerjaan' => $bpjsKetenagakerjaanDenganPotongan,
            'bpjsKetenagakerjaanJkk' => $bpjsKetenagakerjaanJkkDenganPotongan
        ];
    }

    public function getActiveSettingBpjsKetenagakerjaan(): array
    {
        $settings = settings::query()->first();
        $bpjsKetenagakerjaan = [];
        $bpjsKetenagakerjaanJkk = [];
        $dataIdBpjsKetenagakerjaan = [];
        $fieldsBpjsKetenagakerjaan = [
            'bpjs_ketenagakerjaan_jht',
            'bpjs_ketenagakerjaan_jp',
            'bpjs_ketenagakerjaan_jkp',
            'bpjs_ketenagakerjaan_jkm'
        ];

        if ($settings->bpjs_ketenagakerjaan === 'ya') {
            foreach ($fieldsBpjsKetenagakerjaan as $field) {
                if ($settings->$field !== null) {
                    $dataIdBpjsKetenagakerjaan[] = $settings->$field;
                }
            }
            if (!empty($dataIdBpjsKetenagakerjaan)) {
                $bpjsKetenagakerjaan = Ketenagakerjaan::query()->whereIn('id', $dataIdBpjsKetenagakerjaan)->get();
            }
            if ($settings->bpjs_ketenagakerjaan_jkk) {
               $bpjsKetenagakerjaanJkk = KetenagakerjaanJkk::query()->where('id', $settings->bpjs_ketenagakerjaan_jkk)->get();
            }
        }

        return [
            'bpjsKetenagakerjaan' => $bpjsKetenagakerjaan,
            'bpjsKetenagakerjaanJkk' => $bpjsKetenagakerjaanJkk
        ];
    }
}
