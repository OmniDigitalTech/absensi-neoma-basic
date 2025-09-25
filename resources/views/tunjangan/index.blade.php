@extends('templates.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="col-md-12 project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 mt-2 p-0 d-flex">
                        <h4>{{ $title }}</h4>
                    </div>
{{--                    <div class="col-md-6 p-0">--}}
{{--                        <a class="btn btn-primary btn-sm" href="{{ url('/data-cuti/tambah') }}">+ Tambah</a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <center>
                        <a href="{{ url('/tunjangan/tambah') }}" class="btn btn-primary">+ Tambah Data Tunjangan Golongan</a>
                    </center>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="mytable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Golongan</th>
                                <th>Tunjangan Makan</th>
                                <th>Tunjangan Transport</th>
                                <th>Tunjangan Hari Raya</th>
                                <th>Tunjangan Bonus</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->Golongan->name }}</td>
                                    <td>Rp {{ number_format($d->tunjangan_makan) }}</td>
                                    <td>Rp {{ number_format($d->tunjangan_transport) }}</td>
                                    <td>Rp {{ number_format($d->thr) }}</td>
                                    <td>Rp {{ number_format($d->bonus) }}</td>
                                    <td>
                                        <a href="{{ url('/tunjangan/'.$d->id.'/edit') }}" class="btn btn-sm btn-warning"><i class="fa fa-solid fa-edit"></i></a>
                                        <form action="{{ url('/tunjangan/'.$d->id.'/delete') }}" method="post" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <button class="border-0 delete-btn btn btn-danger btn-sm btn-circle" data-item-name="data tunjangan"
                                                    style="background-color: transparent;"> <i
                                                    class="fa fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <br>
@endsection
