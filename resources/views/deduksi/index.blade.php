@extends('templates.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="col-md-12 project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 mt-2 p-0 d-flex">
                        <h4>{{ $title }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <center>
                        <a href="{{ url('/deduksi/tambah-deduksi') }}" class="btn btn-primary">+ Tambah Data Pengurangan Gaji</a>
                    </center>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="mytable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Nominal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>
                                    @if(floor($d->nominal) != $d->nominal)
                                        {{ $d->nominal }} %
                                    @else
                                        Rp {{ number_format($d->nominal) }}
                                    @endif
                                <td>
                                    <a href="{{ url('/deduksi/edit-deduksi/'.$d->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-solid fa-edit"></i></a>
                                    <form action="{{ url('/deduksi/delete-deduksi/'.$d->id) }}" method="post" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="border-0 delete-btn btn btn-danger btn-sm btn-circle" data-item-name="data pengurangan gaji"
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
