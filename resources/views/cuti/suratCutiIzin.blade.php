<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Cuti</title>
    <style>
    body {
        font-family: 'Times New Roman', Times, serif;
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

    .header1 {
        font-size: 20px;
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
    use Carbon\Carbon;
    Carbon::setLocale('id');
    $formattedDate = Carbon::now()->translatedFormat('j F Y');
    @endphp
    <div class="container">
        @if($logo['data'])
        <img src="data:{{ $logo['mime'] }};base64,{{ $logo['data'] }}" style="width: 80px; float:right" alt="Logo">
        @endif
        <h3 style="text-transform: uppercase;">{{ $settings->name }}</h3>
        <span style="font-size: 15px; color:rgb(112, 112, 112)">{{ $settings->alamat }}</span>
        <br>
        <span style="font-size: 15px; color:rgb(112, 112, 112)">{{ $settings->email }} - ({{ $settings->phone }})</span>
        <br>
        <br>
        <br>
        <center>
            <div class="header">
                <span style="font-size:18px"><u><b>SURAT IJIN CUTI</b></u></span><br>
            </div>
        </center>
        <div class="row">
            <div class="col">
                <table style="font-size: 17px">
                    <tbody>
                        <tr>
                            <td style="padding-left: 5px; padding-right: 10px;">Yang bertandatangan di bawah ini Kepala
                                Bagain Umum {{ $settings->name }} memberikan Ijin <b>{{$data->nama_cuti}}</b> kepada :
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table style="font-size: 17px;">
                    <tbody>
                        <tr>
                            <td style="padding-left: 50px; padding-right: 10px;">Nama</td>
                            <td> : </td>
                            <td>{{ $data->User->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 50px; padding-right: 10px">NIK </td>
                            <td> : </td>
                            <td> {{ $data->User->nik }} </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 50px; padding-right: 10px">Unit Kerja/Jabatan</td>
                            <td>:</td>
                            <td>{{ $data->User->jabatan->nama_jabatan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table style="font-size: 17px;">
                    <tbody>
                        <tr>
                            <td style="padding-left: 5px; padding-right: 10px;">
                                Selama
                                <b>{{ $interval }} hari</b> kerja pada
                                @if($data->tanggal_mulai!=$data->tanggal_akhir){{$data->tanggal_mulai}} -
                                {{$data->tanggal_akhir}}@else {{$data->tanggal_mulai}}
                                @endif
                                (Keterangan :
                                <b>{{ $data->nama_cuti}}</b>), dengan ketententuan
                                sebagai berikut :
                            </td>

                        </tr>
                        <tr>
                            <td style="padding-left: 30px; padding-right: 10px;">1. Sewaktu-waktu ada kepentingan
                                Perusahan yang mendesak bersedia untuk masuk kerja;</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px; padding-right: 10px">2. Sebelum melaksanakan cuti agar
                                menyerahkan semua tugas dan tanggung jawab pada atasan langsung termasuk kunci almari,
                                meja, dsb;</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px; padding-right: 10px">3. Cuti dapat dilaksanakan setelah
                                mendapatkan <b>Surat Ijin Cuti.</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table style="font-size: 17px">
                    <tbody>
                        <tr>
                            <td style="padding-left: 5px; padding-right: 10px;">Demikian <b>Surat Ijin Cuti</b> ini
                                dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="col" style="display: flex; flex-direction: column; justify-content: space-between; width: 100%;">
            <div class="container" style="width: 100%; display: flex; flex-direction: column;">
                <div class="header1"
                    style="@if(count($dataSurat) == 1) text-align: right;  @else text-align: center @endif">
                    <span style="font-size:16px; margin-right:16%;"> {{ $settings->alamat_ttd }},
                        {{ $formattedDate }}</span>
                </div>
                <table style="
                            @if(count($dataSurat) == 1)
                                width: 148%;  margin: 0 0 0 auto; margin-right: 9%; float: right
                            @else
                                width: 100%;
                            @endif
                            font-size: 16px; text-align: center;
                        ">
                    <tr>
                        @if(count($dataSurat) >= 1)
                        <td style="padding-left: 10px; padding-right: 10px; font-weight: bold; width: 50%;">
                            {{$dataSurat['hrchy1']['jabatan_id']}}
                        </td>
                        @if(count($dataSurat) >= 2)
                        <td style="padding-left: 10px; padding-right: 10px; font-weight: bold; width: 50%;">
                            {{$dataSurat['hrchy2']['jabatan_id']}}
                        </td>
                        @endif
                        @endif
                    </tr>
                    <tr>
                        @if(count($dataSurat) >= 1)
                        <td style="width: 50%;">
                            <img src="data:{{ $dataSurat['hrchy1']['ttd_mime'] }};base64,{{ $dataSurat['hrchy1']['ttd_data'] }}"
                                style="width: 120px; height:auto;" alt="TTD Cuti">
                        </td>
                        @if(count($dataSurat) >= 2)
                        <td style="width: 50%;">
                            <img src="data:{{ $dataSurat['hrchy2']['ttd_mime'] }};base64,{{ $dataSurat['hrchy2']['ttd_data'] }}"
                                style="width: 120px; height:auto;" alt="TTD Cuti">
                        </td>
                        @endif
                        @endif
                    </tr>
                    <tr>
                        @if(count($dataSurat) >= 1)
                        <td style="padding-left: 10px; padding-right: 10px; font-weight: bold; width: 50%;">
                            {{$dataSurat['hrchy1']['name']}}
                        </td>
                        @if(count($dataSurat) >= 2)
                        <td style="padding-left: 10px; padding-right: 10px; font-weight: bold; width: 50%;">
                            {{$dataSurat['hrchy2']['name']}}
                        </td>
                        @endif
                        @endif
                    </tr>
                    <tr>
                        @if(count($dataSurat) >= 1)
                        <td style="padding-left: 10px; padding-right: 10px; width: 50%;">
                            {{$dataSurat['hrchy1']['nik']}}
                        </td>
                        @if(count($dataSurat) >= 2)
                        <td style="padding-left: 10px; padding-right: 10px; width: 50%;">
                            {{$dataSurat['hrchy2']['nik']}}
                        </td>
                        @endif
                        @endif
                    </tr>
                </table>
            </div>
            <br>
            <br>
            @if(count($dataSurat) == 3)
            <div>
                <table style="width: 100%; font-size: 16px; text-align: center;">
                    <tr>
                        <td style="padding-left: 10px; padding-right: 10px; font-weight: bold;">
                            {{$dataSurat['hrchy3']['jabatan_id']}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img src="data:{{ $dataSurat['hrchy3']['ttd_mime'] }};base64,{{ $dataSurat['hrchy3']['ttd_data'] }}"
                                style="width: 120px; height:auto;" alt="TTD Cuti">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-right: 10px; font-weight: bold;">
                            {{$dataSurat['hrchy3']['name']}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px; padding-right: 10px;">
                            {{$dataSurat['hrchy3']['nik']}}
                        </td>
                    </tr>
                </table>
            </div>
            @endif
        </div>
    </div>
</body>

</html>