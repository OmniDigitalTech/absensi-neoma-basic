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
                        <h4>Data Bpjs Kesehatan</h4>
                    </center>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="mytableksthn" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data_kesehatan as $ksthn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ksthn->name }}</td>
                                <td>Rp. {{ number_format($ksthn->nominal) }}</td>
                                <td>{{ $ksthn->keterangan }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <center>
                        <h4>Data Bpjs Ketenagakerjaan</h4>
                    </center>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="mytablektngkrjn" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data_ketenagakerjaan as $ktngkrjn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ktngkrjn->name }}</td>
                                <td>{{ $ktngkrjn->nominal }} %</td>
                                <td>{{ $ktngkrjn->keterangan }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-header">
                    <center>
                        <h4>Jaminan Kecelakaan Kerja</h4>
                    </center>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="mytablektngkrjnjkk" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tingkat</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data_ketenagakerjaan_jkk as $ktngkrjnjkk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ktngkrjnjkk->name }}</td>
                                <td>{{ $ktngkrjnjkk->nominal }} %</td>
                                <td>{{ $ktngkrjnjkk->keterangan }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection
