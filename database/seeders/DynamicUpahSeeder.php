<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DynamicUpah;

class DynamicUpahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DynamicUpah::create([
            'golongan_id' => 1,
            'nama' => 'Gaji Pokok',
            'jumlah' => 15000000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 1,
            'nama' => 'kehadiran',
            'jumlah' => 1500000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 1,
            'nama' => 'lembur',
            'jumlah' => 0,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 1,
            'nama' => 'oncall',
            'jumlah' => 0,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 2,
            'nama' => 'Gaji Pokok',
            'jumlah' => 8000000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 2,
            'nama' => 'kehadiran',
            'jumlah' => 800000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 2,
            'nama' => 'lembur',
            'jumlah' => 80000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 2,
            'nama' => 'oncall',
            'jumlah' => 80000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 3,
            'nama' => 'Gaji Pokok',
            'jumlah' => 10000000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 3,
            'nama' => 'kehadiran',
            'jumlah' => 1000000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 3,
            'nama' => 'lembur',
            'jumlah' => 100000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 3,
            'nama' => 'oncall',
            'jumlah' => 100000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 4,
            'nama' => 'Gaji Pokok',
            'jumlah' => 7000000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 4,
            'nama' => 'kehadiran',
            'jumlah' => 700000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 4,
            'nama' => 'lembur',
            'jumlah' => 70000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 4,
            'nama' => 'oncall',
            'jumlah' => 70000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 5,
            'nama' => 'Gaji Pokok',
            'jumlah' => 5000000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 5,
            'nama' => 'kehadiran',
            'jumlah' => 500000,
            'keterangan' => 'Bulan',
        ]);

        DynamicUpah::create([
            'golongan_id' => 5,
            'nama' => 'lembur',
            'jumlah' => 50000,
            'keterangan' => 'Jam',
        ]);

        DynamicUpah::create([
            'golongan_id' => 5,
            'nama' => 'oncall',
            'jumlah' => 50000,
            'keterangan' => 'Jam',
        ]);
    }
}
