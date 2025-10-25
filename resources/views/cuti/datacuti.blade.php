@extends('templates.dashboard')
@section('isi')
<div class="row">
    <div class="col-md-12 project-list">
        <div class="card">
            <div class="row">
                <div class="col-md-6 mt-2 p-0 d-flex">
                    <h4>{{ $title }}</h4>
                </div>
{{--                <div class="col-md-6 p-0">--}}
{{--                    <a class="btn btn-primary btn-sm" href="{{ url('/data-cuti/tambah') }}">+ Tambah</a>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form action="{{ url('/data-cuti') }}">
                    <div class="row">
                        <div class="col-3">
                            <select name="user_id" id="user_id" class="form-control selectpicker"
                                    data-live-search="true">
                                <option value="" selected>Pilih Pegawai</option>
                                @foreach($user as $u)
                                    @if(request('user_id') === $u->id)
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
                    <table class="table table-striped" id="mytable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pegawai</th>
                                <th>Nama Cuti</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Akhir</th>
                                <th>Alasan Cuti</th>
                                <th>Foto Cuti</th>
                                <th>Status Cuti</th>
                                <th>Catatan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_cuti as $dc)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dc->User->name }}</td>
                                <td>{{ $dc->nama_cuti }}</td>
                                <td>{{ $dc->tanggal_mulai}}</td>
                                <td>{{ $dc->tanggal_akhir}}</td>
                                <td>{{ $dc->alasan_cuti}}</td>
                                <td>
                                    <img src="{{ url('storage/'.$dc->foto_cuti) }}" style="width: 100px" alt="Image"
                                        onclick="showModal(this, '{{ url('storage/'.$dc->foto_cuti) }}')">
                                </td>
                                <td>
                                    @if($dc->status_cuti === "Diterima")
                                    <span class="badge badge-success">{{ $dc->status_cuti }}</span>
                                    @elseif($dc->status_cuti === "Ditolak")
                                    <span class="badge badge-danger">{{ $dc->status_cuti }}</span>
                                    @else
                                    <span class="badge badge-warning">{{ $dc->status_cuti }}</span>
                                    @endif
                                </td>
                                <td>{{ $dc->catatan}}</td>
                                <td>
                                    <ul class="action">
{{--                                        @if($dc->status_cuti == "Diterima")--}}
                                        <a class="me-2" href="{{ url('/data-cuti/download/'.$dc->id) }}"
                                            target="_blank"><i style="color:green" class="fa fa-solid fa-print"></i></a>
{{--                                        @else--}}
                                        <a class="me-2" href="{{ url('/preview-surat-cuti/'.$dc->id) }}"
                                            target="_blank"><i style="color:cyan" class="fa fa-eye"></i></a>
{{--                                        @endif--}}

                                        @if($dc->status_cuti === "Diterima")
                                        <li class="me-2">
                                            <span class="badge badge-success">Sudah Approve</span>
                                        </li>
                                        @else
                                        <li class="me-2">
                                            <span class="badge badge-warning">Pending Approve</span>
                                        </li>
                                        <li>
                                            <a href="{{ url('/data-cuti/edit/'.$dc->id) }}"><i style="color: blue"
                                                    class="fas fa-edit"></i></a>
                                        </li> @endif
                                        @if($dc->status_cuti === "Diterima")
                                        <li>
                                            <span class="badge badge-success">Sudah Approve</span>
                                        </li>
                                        @else
                                        <li class="delete">
                                            <form action="{{ url('/data-cuti/delete/'.$dc->id) }}" method="post"
                                                class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <!-- <button class="border-0" style="background-color: transparent"
                                                    onClick="return confirm('Are You Sure')"><i
                                                        class="fas fa-trash"></i></button> -->
                                                <button class="border-0 delete-btn" data-item-name="data cuti"
                                                    style="background-color: transparent;"> <i
                                                        class="fa fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mr-4">
                    {{ $data_cuti->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
$(document).ready(function() {
    $('#mulai').change(function() {
        var mulai = $(this).val();
        $('#akhir').val(mulai);
    });
});
</script>
@endpush
@endsection
