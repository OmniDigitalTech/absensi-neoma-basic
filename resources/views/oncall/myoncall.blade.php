@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <form action="{{ url('/my-oncall') }}">
                <span>Filter Rentang Tanggal</span><br><br>
                <div class="form-row">
                    <div class="col-3">
                        <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai"
                            value="{{ request('mulai') }}">
                    </div>
                    <div class="col-3">
                        <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir"
                            value="{{ request('akhir') }}">
                    </div>
                    <div>
                        <button type="submit" id="search" class="form-control btn btn-primary"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table id="tableprintoncall" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pegawai</th>
                        <th>Tanggal</th>
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
                        <td>
                            @php
                            $jam_masuk = explode(" ", $do->jam_masuk);
                            @endphp
                            <span class="badge badge-success">{{ $jam_masuk[1] }}</span>
                        </td>
                        <td>
                            @php
                            $jarak_masuk = explode(".", $do->jarak_masuk);
                            @endphp
                            <a href="{{ url('/maps/'.$do->lat_masuk.'/'.$do->long_masuk.'/'.$do->user_id) }}"
                                class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                            <span class="badge badge-warning">{{ $jarak_masuk[0] }} Meter</span>
                        </td>
                        <td>
                            <img src="{{ url('storage/' . $do->foto_jam_masuk) }}" style="width: 60px">
                        </td>
                        <td>
                            @if ($do->jam_keluar == null)
                            <span class="badge badge-warning">Belum Pulang oncall</span>
                            @else
                            @php
                            $jam_keluar = explode(" ", $do->jam_keluar);
                            @endphp
                            <span class="badge badge-success">{{ $jam_keluar[1] }}</span>
                            @endif
                        </td>
                        <td>
                            @if($do->jam_keluar == null)
                            <span class="badge badge-warning">Belum Pulang oncall</span>
                            @else
                            @php
                            $jarak_keluar = explode(".", $do->jarak_keluar);
                            @endphp
                            <a href="{{ url('/maps/'.$do->lat_keluar.'/'.$do->long_keluar.'/'.$do->user_id) }}"
                                class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                            <span class="badge badge-warning">{{ $jarak_keluar[0] }} Meter</span>
                            @endif
                        </td>
                        <td>
                            @if($do->jam_keluar == null)
                            <span class="badge badge-warning">Belum Pulang oncall</span>
                            @else
                            <img src="{{ url('storage/' . $do->foto_jam_keluar) }}" style="width: 60px">
                            @endif
                        </td>
                        <td>
                            @if($do->jam_keluar == null)
                            <span class="badge badge-warning">Belum Pulang oncall</span>
                            @else
                            @php
                            $total_oncall = $do->total_oncall;
                            $jam = floor($total_oncall / (60 * 60));
                            $menit = $total_oncall - ( $jam * (60 * 60) );
                            $menit2 = floor( $menit / 60 );
                            @endphp
                            <span class="badge badge-success">{{ $jam." Jam ".$menit2." Menit" }}</span>
                            @endif
                        </td>
                        <td>{{ $do->notes }}</td>
                        <td>{{ $do->approvedBy ? $do->approvedBy->name : '' }}</td>
                        <td>
                            @if($do->status == 'Pending')
                            <span class="badge badge-warning">{{ $do->status }}</span>
                            @elseif($do->status == 'Rejected')
                            <span class="badge badge-danger">{{ $do->status }}</span>
                            @else
                            <span class="badge badge-success">{{ $do->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
@endsection