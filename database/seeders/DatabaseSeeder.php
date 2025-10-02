<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\DataCuti;
use App\Models\Deduksi;
use App\Models\Kesehatan;
use App\Models\Ketenagakerjaan;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\KetenagakerjaanJkk;
use App\Models\Lokasi;
use App\Models\Payroll;
use App\Models\ResetCuti;
use App\Models\settings;
use App\Models\Upah;
use App\Models\User;
use App\Models\Shift;
use App\Models\StatusPtkp;
use App\Models\Tunjangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        settings::create([
            'name' => 'CV. Neoma Adi Karya Utama',
            'logo' => 'assets/img/neoma_logo.jpg',
            'alamat' => 'Jl. Delanggu - Juwiring, Bulan, Kec. Wonosari, Klaten',
            'alamat_ttd' => 'Klaten',
            'phone' => '085175310045',
            'email' => 'neoma@gmail.com',
        ]);

        Lokasi::create([
            'nama_lokasi' => 'Kantor Pusat',
            'lat_kantor' => '-7.5694557',
            'long_kantor' => '110.8271453',
            'radius' => '100',
            'status' => 'approved',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Direktur'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Sekretariat',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Manager Adm',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Manager FA',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Manager PR',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Manager IT',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Kepala Bagian Adm'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Kepala Bagian FA'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Kepala Bagian PR'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Kepala Bagian IT',
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Administrasi & Umum'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Keuangan dan Akutansi'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Humas & Pemasaran'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Teknologi Informasi',
        ]);

        Golongan::create([
            'name' => 'DIREKSI'
        ]);

        Golongan::create([
            'name' => 'SEKRETARIS'
        ]);

        Golongan::create([
            'name' => 'MANAGER IT'
        ]);

        Golongan::create([
            'name' => 'KABAG IT'
        ]);

        Golongan::create([
            'name' => 'STAFF IT'
        ]);

        Tunjangan::create([
            'golongan_id' => 1,
            'tunjangan_makan' => '250000.00',
            'tunjangan_transport' => '125000.00',
            'thr' => '5000000.00',
            'bonus' => '2500000.00'
        ]);

        Tunjangan::create([
            'golongan_id' => 2,
            'tunjangan_makan' => '100000.00',
            'tunjangan_transport' => '50000.00',
            'thr' => '2000000.00',
            'bonus' => '500000.00'
        ]);

        Tunjangan::create([
            'golongan_id' => 3,
            'tunjangan_makan' => '150000.00',
            'tunjangan_transport' => '50000.00',
            'thr' => '3000000.00',
            'bonus' => '1500000.00'
        ]);

        Tunjangan::create([
            'golongan_id' => 4,
            'tunjangan_makan' => '60000.00',
            'tunjangan_transport' => '30000.00',
            'thr' => '2000000.00',
            'bonus' => '500000.00'
        ]);

        Tunjangan::create([
            'golongan_id' => 5,
            'tunjangan_makan' => '40000.00',
            'tunjangan_transport' => '20000.00',
            'thr' => '1000000.00',
            'bonus' => '250000.00'
        ]);

        Upah::create([
            'golongan_id' => 1,
            'gaji_pokok' => '15000000',
            'kehadiran' => '1500000',
            'lembur' => '0',
            'oncall' => '0',
        ]);

        Upah::create([
            'golongan_id' => 2,
            'gaji_pokok' => '8000000',
            'kehadiran' => '800000',
            'lembur' => '80000',
            'oncall' => '80000',
        ]);

        Upah::create([
            'golongan_id' => 3,
            'gaji_pokok' => '10000000',
            'kehadiran' => '1000000',
            'lembur' => '100000',
            'oncall' => '100000',
        ]);

        Upah::create([
            'golongan_id' => 4,
            'gaji_pokok' => '7000000',
            'kehadiran' => '700000',
            'lembur' => '70000',
            'oncall' => '70000',
        ]);

        Upah::create([
            'golongan_id' => 5,
            'gaji_pokok' => '3000000',
            'kehadiran' => '300000',
            'lembur' => '30000',
            'oncall' => '30000',
        ]);

        Deduksi::create([
            'nama' => 'izin',
            'nominal' => '5000',
            'keterangan' => 'Hari'
        ]);

        Deduksi::create([
            'nama' => 'terlambat',
            'nominal' => '10000',
            'keterangan' => 'Hari'
        ]);

        Deduksi::create([
            'nama' => 'mangkir',
            'nominal' => '50000',
            'keterangan' => 'Hari'
        ]);

//        Deduksi::create([
//            'nama' => 'bpjs kesehatan kelas 1',
//            'nominal' => '150000',
//            'keterangan' => 'Bulan'
//        ]);
//
//        Deduksi::create([
//            'nama' => 'bpjs kesehatan kelas 2',
//            'nominal' => '100000',
//            'keterangan' => 'Bulan'
//        ]);
//
//        Deduksi::create([
//            'nama' => 'bpjs ketenagakerjaan JHT',
//            'nominal' => '5.7',
//            'keterangan' => 'Bulan'
//        ]);

        Kesehatan::create([
            'name' => 'bpjs kesehatan kelas 1',
            'nominal' => '150000',
            'keterangan' => 'Bulan'
        ]);

        Kesehatan::create([
            'name' => 'bpjs kesehatan kelas 2',
            'nominal' => '100000',
            'keterangan' => 'Bulan'
        ]);

        Kesehatan::create([
            'name' => 'bpjs kesehatan kelas 3',
            'nominal' => '35000',
            'keterangan' => 'Bulan'
        ]);

        Ketenagakerjaan::create([
            'name' => 'Jaminan Hari Tua',
            'nominal' => '2',
            'keterangan' => 'Bulan'
        ]);

        Ketenagakerjaan::create([
            'name' => 'Jaminan Pensiun',
            'nominal' => '1',
            'keterangan' => 'Bulan'
        ]);

        Ketenagakerjaan::create([
            'name' => 'Jaminan Kematian',
            'nominal' => '0.3',
            'keterangan' => 'Bulan'
        ]);

        Ketenagakerjaan::create([
            'name' => 'Jaminan Kehilangan Pekerjaan',
            'nominal' => '0.46',
            'keterangan' => 'Bulan'
        ]);

        KetenagakerjaanJkk::create([
            'name' => 'Sangat Tinggi',
            'nominal' => '0.870',
            'keterangan' => 'Bulan'
        ]);

        KetenagakerjaanJkk::create([
            'name' => 'Tinggi',
            'nominal' => '0.635',
            'keterangan' => 'Bulan'
        ]);

        KetenagakerjaanJkk::create([
            'name' => 'Sedang',
            'nominal' => '0.445',
            'keterangan' => 'Bulan'
        ]);

        KetenagakerjaanJkk::create([
            'name' => 'Rendah',
            'nominal' => '0.270',
            'keterangan' => 'Bulan'
        ]);

        DataCuti::create([
            'nama' => 'cuti',
            'jumlah' => '10',
        ]);

        DataCuti::create([
            'nama' => 'izin masuk',
            'jumlah' => '10',
        ]);

        DataCuti::create([
            'nama' => 'izin telat',
            'jumlah' => '10',
        ]);

        DataCuti::create([
            'nama' => 'izin pulang cepat',
            'jumlah' => '10',
        ]);

        Shift::create([
            'nama_shift' => "Libur",
            'jam_masuk' => "00:00",
            'jam_keluar' => "00:00",
        ]);

        Shift::create([
            'nama_shift' => "Office",
            'jam_masuk' => "08:00",
            'jam_keluar' => "17:00",
        ]);

        Shift::create([
            'nama_shift' => "Siang",
            'jam_masuk' => "13:00",
            'jam_keluar' => "21:00",
        ]);

        Shift::create([
            'nama_shift' => "Malam",
            'jam_masuk' => "21:00",
            'jam_keluar' => "07:00",
        ]);

        ResetCuti::create([
            'izin_cuti' => '10',
            'izin_dinas_luar' => '10',
            'izin_sakit' => '10',
            'izin_cek_kesehatan' => '10',
            'izin_keperluan_pribadi' => '10',
            'izin_lainnya' => '10',
            'izin_telat' => '10',
            'izin_pulang_cepat' => '10',
        ]);

        StatusPtkp::create([
            'name' => 'TK/0',
            'ptkp_2016' => '54000000',
            'ptkp_2015' => '36000000',
            'ptkp_2009_2012' => '15840000',
        ]);

        StatusPtkp::create([
            'name' => 'TK/1',
            'ptkp_2016' => '58500000',
            'ptkp_2015' => '39000000',
            'ptkp_2009_2012' => '17160000',
        ]);

        StatusPtkp::create([
            'name' => 'TK/2',
            'ptkp_2016' => '63000000',
            'ptkp_2015' => '42000000',
            'ptkp_2009_2012' => '18480000',
        ]);

        StatusPtkp::create([
            'name' => 'TK/3',
            'ptkp_2016' => '67500000',
            'ptkp_2015' => '45000000',
            'ptkp_2009_2012' => '19800000',
        ]);

        Counter::create([
            'name' => 'Gaji',
            'text' => 'GJ',
            'counter' => 0
        ]);

        User::create([
            'name' => 'Direktur',
            'nik' => '112.111',
            'email' => 'direktur@gmail.com',
            'telepon' => '123456789',
            'username' => 'direktur',
            'password' => Hash::make('direktur123'),
            'tgl_lahir' => date('Y-m-d'),
            'gender' => 'Laki-Laki',
            'tgl_join' => '2022-01-28',
            'status_nikah' => 'Lajang',
            'alamat' => 'jl. Direktur test',
            'is_admin' => 'director',
            'tipe_karyawan' => 'tetap',
            'jabatan_id' => '1',
            'golongan_id' => '1',
            'lokasi_id' => '1',
            'rekening' => '5112231',
//            'izin_cuti' => '10',
//            'izin_lainnya' => '10',
//            'izin_telat' => '10',
//            'izin_pulang_cepat' => '10',
//            'gaji_pokok' => 10000000,
//            'makan_transport' => 900000,
//            'lembur' => 20000,
//            'kehadiran' => 800000,
//            'thr' => 700000,
//            'bonus' => 600000,
//            'izin' => 0,
//            'terlambat' => 0,
//            'mangkir' => 0,
//            'saldo_kasbon' => 0,
        ]);

        User::create([
            'name' => 'Sekretaris 1',
            'nik' => '113.111',
            'email' => 'direktur@gmail.com',
            'telepon' => '123456789',
            'username' => 'direktur',
            'password' => Hash::make('direktur123'),
            'tgl_lahir' => date('Y-m-d'),
            'gender' => 'Laki-Laki',
            'tgl_join' => '2022-01-28',
            'status_nikah' => 'Lajang',
            'alamat' => 'jl. Direktur test',
            'is_admin' => 'director',
            'tipe_karyawan' => 'tetap',
            'jabatan_id' => '2',
            'golongan_id' => '2',
            'lokasi_id' => '1',
            'rekening' => '5112231',
//            'izin_cuti' => '10',
//            'izin_lainnya' => '10',
//            'izin_telat' => '10',
//            'izin_pulang_cepat' => '10',
//            'gaji_pokok' => 10000000,
//            'makan_transport' => 900000,
//            'lembur' => 20000,
//            'kehadiran' => 800000,
//            'thr' => 700000,
//            'bonus' => 600000,
//            'izin' => 0,
//            'terlambat' => 0,
//            'mangkir' => 0,
//            'saldo_kasbon' => 0,
        ])->assignManager(2, 1);

        User::create([
            'name' => 'Manager IT',
            'nik' => '114.111',
            'email' => 'managerit@gmail.com',
            'telepon' => '123456789',
            'username' => 'manager it',
            'password' => Hash::make('managerit123'),
            'tgl_lahir' => date('Y-m-d'),
            'gender' => 'Laki-Laki',
            'tgl_join' => '2022-01-28',
            'status_nikah' => 'Lajang',
            'alamat' => 'jl. Manager IT test',
            'is_admin' => 'manager',
            'tipe_karyawan' => 'tetap',
            'jabatan_id' => '6',
            'golongan_id' => '3',
            'lokasi_id' => '1',
            'rekening' => '5112231',
//            'izin_cuti' => '10',
//            'izin_lainnya' => '10',
//            'izin_telat' => '10',
//            'izin_pulang_cepat' => '10',
//            'gaji_pokok' => 10000000,
//            'makan_transport' => 900000,
//            'lembur' => 20000,
//            'kehadiran' => 800000,
//            'thr' => 700000,
//            'bonus' => 600000,
//            'izin' => 0,
//            'terlambat' => 0,
//            'mangkir' => 0,
//            'saldo_kasbon' => 0,
        ])->assignManager(6, 1);

        User::create([
            'name' => 'Head IT',
            'nik' => '115.111',
            'email' => 'headit@gmail.com',
            'telepon' => '123456789',
            'username' => 'head it',
            'password' => Hash::make('headit123'),
            'tgl_lahir' => date('Y-m-d'),
            'gender' => 'Laki-Laki',
            'tgl_join' => '2022-01-28',
            'status_nikah' => 'Lajang',
            'alamat' => 'jl. Head IT test',
            'is_admin' => 'head',
            'tipe_karyawan' => 'tetap',
            'jabatan_id' => '10',
            'golongan_id' => '4',
            'lokasi_id' => '1',
            'rekening' => '5112231',
//            'izin_cuti' => '10',
//            'izin_lainnya' => '10',
//            'izin_telat' => '10',
//            'izin_pulang_cepat' => '10',
//            'gaji_pokok' => 10000000,
//            'makan_transport' => 900000,
//            'lembur' => 20000,
//            'kehadiran' => 800000,
//            'thr' => 700000,
//            'bonus' => 600000,
//            'izin' => 0,
//            'terlambat' => 0,
//            'mangkir' => 0,
//            'saldo_kasbon' => 0,
        ])->assignManager(10, 3);

        $user = User::create([
            'name' => 'Admin',
            'nik' => '111.111',
            'email' => 'admin@gmail.com',
            'telepon' => '0987654321',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'tgl_lahir' => date('Y-m-d'),
            'gender' => 'Laki-Laki',
            'tgl_join' => '1998-01-26',
            'status_nikah' => 'Menikah',
            'alamat' => 'jl. Admin test',
            'is_admin' => 'admin',
            'tipe_karyawan' => 'tetap',
            'jabatan_id' => '14',
            'golongan_id' => '5',
            'lokasi_id' => '1',
            'rekening' => '5112231',
//            'izin_cuti' => '10',
//            'izin_lainnya' => '10',
//            'izin_telat' => '10',
//            'izin_pulang_cepat' => '10',
//            'gaji_pokok' => 7000000,
//            'makan_transport' => 800000,
//            'lembur' => 20000,
//            'kehadiran' => 300000,
//            'thr' => 200000,
//            'bonus' => 200000,
//            'izin' => 100000,
//            'terlambat' => 100000,
//            'mangkir' => 200000,
//            'saldo_kasbon' => 220000,
    ]);

        if ($user) {
            $user->assignManager(14, 4);
            $user->assignLokasiCreatedBy(1, $user->id);
        }

        User::create([
            'name' => 'User IT',
            'nik' => '116.111',
            'email' => 'user@gmail.com',
            'telepon' => '123456789',
            'username' => 'user it',
            'password' => Hash::make('userit123'),
            'tgl_lahir' => date('Y-m-d'),
            'gender' => 'Laki-Laki',
            'tgl_join' => '2022-01-28',
            'status_nikah' => 'Lajang',
            'alamat' => 'jl. User IT test',
            'is_admin' => 'user',
            'tipe_karyawan' => 'tetap',
            'jabatan_id' => '14',
            'golongan_id' => '5',
            'lokasi_id' => '1',
            'rekening' => '5112231',
//            'izin_cuti' => '10',
//            'izin_lainnya' => '10',
//            'izin_telat' => '10',
//            'izin_pulang_cepat' => '10',
//            'gaji_pokok' => 10000000,
//            'makan_transport' => 900000,
//            'lembur' => 20000,
//            'kehadiran' => 800000,
//            'thr' => 700000,
//            'bonus' => 600000,
//            'izin' => 100000,
//            'terlambat' => 100000,
//            'mangkir' => 200000,
//            'saldo_kasbon' => 4000000,
        ]);
    }
}
