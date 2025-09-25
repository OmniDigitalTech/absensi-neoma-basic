@extends('templates.dashboard')
@section('isi')
<div class="row">
    <div class="col-md-12 project-list">
        <div class="card">
            <div class="row">
                <div class="col-md-6 mt-2 p-0 d-flex">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="col-md-6 p-0">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form action="{{ url('/data-oncall') }}">
                    <div class="row">
                        <div class="col-3">
                            <select name="user_id" id="user_id" class="form-control selectpicker"
                                data-live-search="true">
                                <option value="" selected>Pilih Pegawai</option>
                                @foreach($user as $u)
                                @if(request('user_id') == $u->id)
                                <option value="{{ $u->id }}" selected>{{ $u->name }}</option>
                                @else
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai"
                                id="mulai" value="{{ request('mulai') }}">
                        </div>
                        <div class="col-3">
                            <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir"
                                id="akhir" value="{{ request('akhir') }}">
                        </div>
                        <div class="col-3">
                            <button type="submit" id="search" class="border-0 mt-3"
                                style="background-color: transparent;"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="mytable" class="table table-striped">
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
                                <th>Total Lembur</th>
                                <th>Notes</th>
                                <th>User Approval</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_oncall as $do)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $do->User->name }}</td>
                                <td>{{ $do->tanggal }}</td>
                                <td>{{ $do->keterangan}}</td>
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
                                    <img src="{{ url('storage/' . $do->foto_jam_masuk) }}" style="width: 60px"
                                        onclick="showModal(this, '{{ url('storage/'.$do->foto_jam_masuk) }}')">
                                </td>
                                <td>
                                    @if ($do->jam_keluar == null)
                                    <span class="badge badge-warning">Belum Pulang OnCall</span>
                                    @else
                                    @php
                                    $jam_keluar = explode(" ", $do->jam_keluar);
                                    @endphp
                                    <span class="badge badge-success">{{ $jam_keluar[1] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($do->jam_keluar == null)
                                    <span class="badge badge-warning">Belum Pulang OnCall</span>
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
                                    <span class="badge badge-warning">Belum Pulang OnCall</span>
                                    @else
                                    <img src="{{ url('storage/' . $do->foto_jam_keluar) }}" style="width: 60px"
                                        onclick="showModal(this, '{{ url('storage/'.$do->foto_jam_keluar) }}')">
                                    @endif
                                </td>
                                <td>
                                    @if($do->jam_keluar == null)
                                    <span class="badge badge-warning">Belum Pulang OnCall</span>
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
                                <td>
                                    @if ($do->status !== 'Approved' && $do->jam_keluar !== null)
                                    <ul class="action">
                                        <li>
                                            <button class="border-0" style="background-color: transparent" type="button"
                                                data-bs-toggle="modal" data-original-title="test"
                                                data-bs-target="#exampleModal"><i style="color:blue"
                                                    class="fa fa-check-circle"></i></button>

                                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Import
                                                                Users
                                                            </h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ url('/data-lembur/approval/'.$do->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    @php
                                                                    $status = array(
                                                                    [
                                                                    "status" => "Approved",
                                                                    "status_name" => "Approve"
                                                                    ],
                                                                    [
                                                                    "status" => "Rejected",
                                                                    "status_name" => "Reject"
                                                                    ]
                                                                    );
                                                                    @endphp
                                                                    <label for="status">Status</label>
                                                                    <select name="status" id="status"
                                                                        class="form-control selectpicker"
                                                                        data-live-search="true">
                                                                        <option value="">Pilih Status</option>
                                                                        @foreach ($status as $s)
                                                                        @if(old('status', $do->status) ==
                                                                        $s["status"])
                                                                        <option value="{{ $s["status"] }}" selected>
                                                                            {{ $s["status_name"] }}</option>
                                                                        @else
                                                                        <option value="{{ $s["status"] }}">
                                                                            {{ $s["status_name"] }}</option>
                                                                        @endif
                                                                        @endforeach
                                                                    </select>
                                                                    @error('status')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="notes"
                                                                        class="col-form-label">Notes:</label>
                                                                    <textarea class="form-control" id="notes"
                                                                        name="notes">{{ old('notes') }}</textarea>
                                                                    @error('notes')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                                <input type="hidden" name="approved_by"
                                                                    value="{{ auth()->user()->id }}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button class="btn btn-secondary" type="submit">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end me-4 mt-4">
                    {{ $data_oncall->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
