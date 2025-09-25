@extends('templates.app')
@section('container')
<div class="card-secton transfer-section">
    <div class="tf-container">
        <div class="tf-balance-box">
            <form action="{{ url('/my-oncall') }}">
                <div class="row">
                    <div class="col-5">
                        <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai"
                            value="{{ request('mulai') }}">
                    </div>
                    <div class="col-5">
                        <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir"
                            value="{{ request('akhir') }}">
                    </div>
                    <div class="col-1">
                        <button type="submit" id="search" class="form-control btn" style="width: 25px"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="tf-spacing-20"></div>
<div class="transfer-content">
    <div class="tf-container">
        <table id="tablePayroll" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal</th>
                    <th>Mengerjakan</th>
                    <th>Jam Masuk</th>
                    <th>Lokasi Masuk</th>
                    <th>Foto Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi Pulang</th>
                    <th>Foto Pulang</th>
                    <th>Total Oncall</th>
                    <th>Notes</th>
                    <th>User Approval</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_oncall as $do)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $do->User->name }}</td>
                    <td>{{ $do->tanggal }}</td>
                    <td>{{ $do->keterangan }}</td>
                    <td>
                        @php
                        $jam_masuk = explode(" ", $do->jam_masuk);
                        @endphp
                        {{ $jam_masuk[1] }}
                    </td>
                    <td>
                        @php
                        $jarak_masuk = explode(".", $do->jarak_masuk);
                        @endphp
                        <a href="{{ url('/maps/'.$do->lat_masuk.'/'.$do->long_masuk.'/'.$do->user_id) }}"
                            class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                        <br>
                        {{ $jarak_masuk[0] }} Meter
                    </td>
                    <td>
                        <img src="{{ url('storage/' . $do->foto_jam_masuk) }}" style="width: 60px"
                            onclick="showModal(this, '{{ url('storage/'.$do->foto_jam_masuk) }}')">
                    </td>
                    <td>
                        @if ($do->jam_keluar == null)
                        Belum Pulang Oncall
                        @else
                        @php
                        $jam_keluar = explode(" ", $do->jam_keluar);
                        @endphp
                        {{ $jam_keluar[1] }}
                        @endif
                    </td>
                    <td>
                        @if($do->jam_keluar == null)
                        Belum Pulang Oncall
                        @else
                        @php
                        $jarak_keluar = explode(".", $do->jarak_keluar);
                        @endphp
                        <a href="{{ url('/maps/'.$do->lat_keluar.'/'.$do->long_keluar.'/'.$do->user_id) }}"
                            class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                        <br>
                        {{ $jarak_keluar[0] }} Meter
                        @endif
                    </td>
                    <td>
                        @if($do->jam_keluar == null)
                        Belum Pulang Oncall
                        @else
                        <img src="{{ url('storage/' . $do->foto_jam_keluar) }}" style="width: 60px"
                            onclick="showModal(this, '{{ url('storage/'.$do->foto_jam_keluar) }}')">
                        @endif
                    </td>
                    <td>
                        @if($do->jam_keluar == null)
                        Belum Pulang Oncall
                        @else
                        @php
                        $total_oncall = $do->total_oncall;
                        $jam = floor($total_oncall / (60 * 60));
                        $menit = $total_oncall - ( $jam * (60 * 60) );
                        $menit2 = floor( $menit / 60 );
                        @endphp
                        {{ $jam." Jam ".$menit2." Menit" }}
                        @endif
                    </td>
                    <td>{{ $do->notes }}</td>
                    <td>{{ $do->approvedBy ? $do->approvedBy->name : '' }}</td>
                    <td>
                        @if($do->status == 'Pending')
                        {{ $do->status }}
                        @elseif($do->status == 'Rejected')
                        {{ $do->status }}
                        @else
                        {{ $do->status }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mr-4">
        {{ $data_oncall->links() }}
    </div>
</div>
<br>
<br>
<br>
<br>
@endsection