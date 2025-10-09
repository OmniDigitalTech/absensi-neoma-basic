<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .header {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .total {
        margin-top: 20px;
        font-weight: bold;
    }

    .row::after {
        content: "";
        clear: both;
        display: table;
    }

    .col {
        box-sizing: border-box;
        float: left;
    }

    .col-1 {
        width: 8.33%;
    }

    .col-2 {
        width: 16.66%;
    }

    .col-3 {
        width: 25%;
    }

    .col-4 {
        width: 33.33%;
    }

    .col-6 {
        width: 50%;
    }

    .col-8 {
        width: 66.66%;
    }

    .col-12 {
        width: 100%;
    }
    </style>
</head>

<body>
    @php
        $settings = App\Models\settings::first();
        $logo_path = storage_path('app/public/' . $settings->logo);
    if (file_exists($logo_path)) {
        $logo_mime = mime_content_type($logo_path);
        $logo_data = base64_encode(file_get_contents($logo_path));
    } else {
        $logo_mime = null;
        $logo_data = null;
    }
    @endphp
    <div class="container">
        @if($logo_data)
            <img src="data:{{ $logo_mime }};base64,{{ $logo_data }}" style="width: 80px; float:right" alt="Logo">
        @endif
            <h3 style="text-transform: uppercase;">{{ $settings->name }}</h3>
            <span style="font-size: 10px; color:rgb(112, 112, 112)">{{ $settings->alamat }}</span>
            <br>
            <span style="font-size: 10px; color:rgb(112, 112, 112)">{{ $settings->email }} - ({{ $settings->phone }})</span>
            <hr>
        <div style="text-align: center;">
            <div class="header">Slip Gaji CV. Neoma</div>
        </div>
        @php
            if ($data_payroll->bulan === 1) {
                $bulan = "Januari";
            } elseif ($data_payroll->bulan === 2) {
                $bulan = "Februari";
            } elseif ($data_payroll->bulan === 3) {
                $bulan = "Maret";
            } elseif ($data_payroll->bulan === 4) {
                $bulan = "April";
            } elseif ($data_payroll->bulan === 5) {
                $bulan = "Mei";
            } elseif ($data_payroll->bulan === 6) {
                $bulan = "Juni";
            } elseif ($data_payroll->bulan === 7) {
                $bulan = "Juli";
            } elseif ($data_payroll->bulan === 8) {
                $bulan = "Agustus";
            } elseif ($data_payroll->bulan === 9) {
                $bulan = "September";
            } elseif ($data_payroll->bulan === 10) {
                $bulan = "Oktober";
            } elseif ($data_payroll->bulan === 11) {
                $bulan = "November";
            } else {
                $bulan = "Desember";
            }
        @endphp
        <div class="row">
            <div class="col">
                <table style="font-size: 13px">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $data_payroll->User->name }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ $data_payroll->User->jabatan->nama_jabatan }}</td>
                        </tr>
                        <tr>
                            <td>Rekening</td>
                            <td>:</td>
                            <td>{{ $data_payroll->User->rekening }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col" style="margin-left: 28%">
                <table style="font-size: 13px">
                    <tbody>
                        <!-- <tr>
                            <td>Tgl Gabung</td>
                            <td>:</td>
                            <td>{{ $data_payroll->User->tgl_join }}</td>
                        </tr> -->
                        <tr>
                            <td>Bulan</td>
                            <td>:</td>
                            <td>{{ $bulan . ' ' . $data_payroll->tahun }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Cetak Slip</td>
                            <td>:</td>
                            <td>{{ date('Y-m-d') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <br>
        <div style="font-style:italic; text-decoration: underline; font-size:14px">RINCIAN GAJI BULAN INI</div>
        <br>

        <table style="font-size: 13px">
            <tbody>
                @if($data_payroll->gaji_pokok > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Gaji Pokok</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->gaji_pokok) }}</td>
                </tr>
                @endif
                @if($data_payroll->uang_makan > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Uang Makan</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->uang_makan) }}
                    </td>
                </tr>
                @endif
                @if($data_payroll->uang_transport > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Uang Transport</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->uang_transport) }}
                    </td>
                </tr>
                @endif
                @if($data_payroll->total_kehadiran > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Kehadiran 100%</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_kehadiran) }}
                    </td>
                </tr>
                @endif
                @if($data_payroll->total_lembur > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Lembur</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px">{{ $data_payroll->jumlah_lembur }}</td>
                    <td style="padding-left: 10px; padding-right: 10px">Jam</td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_lembur) }}</td>
                </tr>
                @endif
                @if($data_payroll->total_oncal > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">On Call</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px">{{ $data_payroll->jumlah_oncall }}</td>
                    <td style="padding-left: 10px; padding-right: 10px">Jam</td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_oncall) }}</td>
                </tr>
                @endif
                @if($data_payroll->total_bonus > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Bonus</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_bonus) }}</td>
                </tr>
                @endif
                @if($data_payroll->total_thr > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px">Tunjangan Hari Raya</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_thr) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">_______________________</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 10px; font-weight: bold;">Subtotal</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_penjumlahan) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <br>
        <div style="font-style:italic; text-decoration: underline; font-size:14px">DIKURANGI</div>
        <br>

        <table style="font-size: 13px">
            <tbody>
                @if($data_payroll->total_terlambat > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 17px">Keterlambatan</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px">{{ $data_payroll->jumlah_terlambat }}</td>
                    <td style="padding-left: 10px; padding-right: 10px">Kali</td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_terlambat) }}
                    </td>
                </tr>
                @endif
                @if($data_payroll->total_mangkir > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 17px">Mangkir</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px">{{ $data_payroll->jumlah_mangkir }}</td>
                    <td style="padding-left: 10px; padding-right: 10px">Hari</td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_mangkir) }}
                    </td>
                </tr>
                @endif
                @if($data_payroll->total_izin > 0)
                <tr>
                    <td style="padding-left: 10px; padding-right: 17px">Izin</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px">{{ $data_payroll->jumlah_izin }}</td>
                    <td style="padding-left: 10px; padding-right: 10px">Hari</td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_izin) }}</td>
                </tr>
                @endif
{{--                <tr>--}}
{{--                    <td style="padding-left: 10px; padding-right: 17px">Kasbon</td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px">:</td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px"></td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px"></td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->bayar_kasbon) }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="padding-left: 10px; padding-right: 17px">Loss</td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px">:</td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px"></td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px"></td>--}}
{{--                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->loss) }}</td>--}}
{{--                </tr>--}}

{{--                @if(!empty($data_bpjs_ketenagakerjaan))--}}
{{--                    <tr>--}}
{{--                        <td style="padding-left: 10px; padding-right: 17px">BPJS Kesehatan</td>--}}
{{--                        <td style="padding-left: 10px; padding-right: 10px">:</td>--}}
{{--                        <td style="padding-left: 10px; padding-right: 10px">{{ $data_bpjs_kesehatan[0]['kelas'] }}</td>--}}
{{--                        <td style="padding-left: 10px; padding-right: 10px">Kelas</td>--}}
{{--                        <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->potongan_bpjs_kesehatan) }}</td>--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--                @if(!empty($data_bpjs_ketenagakerjaan))--}}
{{--                    @foreach($data_bpjs_ketenagakerjaan as $bpjs_ketenagakerjaan)--}}
{{--                        <tr>--}}
{{--                            <td style="padding-left: 10px; padding-right: 17px">{{ $bpjs_ketenagakerjaan->name }}</td>--}}
{{--                            <td style="padding-left: 10px; padding-right: 10px">:</td>--}}
{{--                            <td style="padding-left: 10px; padding-right: 10px">{{ $bpjs_ketenagakerjaan->nominal }}</td>--}}
{{--                            <td style="padding-left: 10px; padding-right: 10px">%</td>--}}
{{--                            <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($bpjs_ketenagakerjaan->nilai_potongan) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    @if(!empty($data_bpjs_ketenagakerjaan_jkk))--}}
{{--                        @foreach($data_bpjs_ketenagakerjaan_jkk as $bpjs_ketenagakerjaan_jkk)--}}
{{--                            <tr>--}}
{{--                                <td style="padding-left: 10px; padding-right: 17px">Jaminan Kecelakaan<br>Kerja - {{ $bpjs_ketenagakerjaan_jkk->name }}</td>--}}
{{--                                <td style="padding-left: 10px; padding-right: 10px">:</td>--}}
{{--                                <td style="padding-left: 10px; padding-right: 10px">{{ $bpjs_ketenagakerjaan_jkk->nominal }}</td>--}}
{{--                                <td style="padding-left: 10px; padding-right: 10px">%</td>--}}
{{--                                <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->potongan_Jaminan_Kecelakaan_Kerja) }}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                @endif--}}

                 🩺 BPJS KESEHATAN
                @if(!empty($data_bpjs_kesehatan))
                    <tr>
                        <td style="padding-left: 10px; padding-right: 17px">BPJS Kesehatan</td>
                        <td style="padding-left: 10px; padding-right: 10px">:</td>
                        <td style="padding-left: 10px; padding-right: 10px">
                            {{ $data_bpjs_kesehatan[0]['kelas'] ?? '-' }}
                        </td>
                        <td style="padding-left: 10px; padding-right: 10px">Kelas</td>
                        <td style="padding-left: 10px; padding-right: 10px">
                            Rp {{ number_format($data_payroll->potongan_bpjs_kesehatan ?? 0) }}
                        </td>
                    </tr>
                @endif

                 ⚙️ BPJS KETENAGAKERJAAN
                @if(!empty($data_bpjs_ketenagakerjaan))
                    @foreach($data_bpjs_ketenagakerjaan as $bpjs_ketenagakerjaan)
                        <tr>
                            <td style="padding-left: 10px; padding-right: 17px">{{ $bpjs_ketenagakerjaan->name ?? '-' }}</td>
                            <td style="padding-left: 10px; padding-right: 10px">:</td>
                            <td style="padding-left: 10px; padding-right: 10px">{{ $bpjs_ketenagakerjaan->nominal ?? 0 }}</td>
                            <td style="padding-left: 10px; padding-right: 10px">%</td>
                            <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($bpjs_ketenagakerjaan->nilai_potongan ?? 0) }}</td>
                        </tr>
                    @endforeach
                @endif

                 🛡️ BPJS KETENAGAKERJAAN JKK
                @if(!empty($data_bpjs_ketenagakerjaan_jkk))
                    @foreach($data_bpjs_ketenagakerjaan_jkk as $bpjs_ketenagakerjaan_jkk)
                        <tr>
                            <td style="padding-left: 10px; padding-right: 17px">
                                Jaminan Kecelakaan<br>Kerja - {{ $bpjs_ketenagakerjaan_jkk->name ?? '-' }}
                            </td>
                            <td style="padding-left: 10px; padding-right: 10px">:</td>
                            <td style="padding-left: 10px; padding-right: 10px">{{ $bpjs_ketenagakerjaan_jkk->nominal ?? 0 }}</td>
                            <td style="padding-left: 10px; padding-right: 10px">%</td>
                            <td style="padding-left: 10px; padding-right: 10px">
                                Rp {{ number_format($data_payroll->potongan_Jaminan_Kecelakaan_Kerja ?? 0) }}
                            </td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td style="padding-left: 10px; padding-right: 17px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">_______________________</td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-right: 17px; font-weight: bold;">Subtotal</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px">Rp {{ number_format($data_payroll->total_pengurangan) }}
                    </td>
                </tr>
                <br>
                <tr>
                    <td style="padding-left: 10px; padding-right: 17px; font-weight: bold;">GAJI YANG DITERIMA</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px"></td>
                    <td style="padding-left: 10px; padding-right: 10px; font-weight: bold; border: 1px solid #000;">Rp
                        {{ number_format($data_payroll->grand_total) }}</td>
                </tr>
                <br>
                <tr>
                    <td style="padding-left: 10px; padding-right: 17px">Sisa Cuti</td>
                    <td style="padding-left: 10px; padding-right: 10px">:</td>
                    <td style="padding-left: 10px; padding-right: 10px">{{ $sisa_cuti }}</td>
                    <td style="padding-left: 10px; padding-right: 10px">Kali</td>
                    <td style="padding-left: 10px; padding-right: 10px">/ Tahun</td>
                </tr>
            </tbody>

        </table>
    </div>
</body>

</html>
