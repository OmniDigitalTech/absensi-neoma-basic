@extends('templates.app')
@section('container')
<div class="card-secton transfer-section">
    <div class="tf-container">
        <div class="tf-balance-box">
            <form class="tf-form p-4" method="post" action="{{ url('/cuti/tambah') }}" enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="group-input">
                    <label for="user_id">Nama Pegawai</label>
                    <input type="text" id="user_name" name="user_name" value="{{ $data_user->name }}" readonly>
                    <input type="hidden" name="user_id" id="user_id" value="{{ $data_user->id }}">
                </div>
                <div class="group-input">
                    @php
                    $izin_cuti = $data_user->izin_cuti;
                    $izin_lainnya = $data_user->izin_lainnya;
                    $izin_telat = $data_user->izin_telat;
                    $izin_pulang_cepat = $data_user->izin_pulang_cepat;

                    $data_cuti = array(
                    [
                    'nama' => 'Cuti',
                    'nama_cuti' => 'Cuti ('.$izin_cuti.')'
                    ],
                    [
                    'nama' => 'Izin Masuk',
                    'nama_cuti' => 'Izin Masuk ('.$izin_lainnya.')'
                    ],
                    [
                    'nama' => 'Izin Telat',
                    'nama_cuti' => 'Izin Telat ('.$izin_telat.')'
                    ],
                    [
                    'nama' => 'Izin Pulang Cepat',
                    'nama_cuti' => 'Izin Pulang Cepat ('.$izin_pulang_cepat.')'
                    ]
                    );
                    @endphp
                    <label for="nama_cuti" style="z-index: 1;">Jenis Cuti / Izin</label>
                    <select class="select2 @error('nama_cuti') is-invalid @enderror" id="nama_cuti" name="nama_cuti"
                        data-live-search="true">
                        <option value="">Pilih Cuti</option>
                        @foreach ($data_cuti as $dc)
                        @if(old('nama_cuti') == $dc["nama"])
                        <option value="{{ $dc["nama"] }}" selected>{{ $dc["nama_cuti"] }}</option>
                        @else
                        <option value="{{ $dc["nama"] }}">{{ $dc["nama_cuti"] }}</option>
                        @endif
                        @endforeach
                    </select>
                    @error('nama_cuti')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="group-input">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="datetime" class="@error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai"
                        id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" placeholder="Pilih Tanggal Mulai">
                    @error('tanggal_mulai')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="group-input">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="datetime" class="@error('tanggal_akhir') is-invalid @enderror" name="tanggal_akhir"
                        id="tanggal_akhir" value="{{ old('tanggal_akhir') }}" placeholder="Pilih Tanggal Akhir">
                    @error('tanggal_akhir')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <input type="hidden" name="tanggal">

                <div class="group-input">
                    <input type="file" name="foto_cuti" id="foto_cuti"
                        class="form-control @error('foto_cuti') is-invalid @enderror">
                    @error('foto_cuti')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="group-input">
                    <label for="alasan_cuti">Alasan Cuti / Izin</label>
                    <input type="text" class="form-control @error('alasan_cuti') is-invalid @enderror" id="alasan_cuti"
                        name="alasan_cuti" value="{{ old('alasan_cuti') }}">
                    @error('alasan_cuti')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <input type="hidden" name="status_cuti">
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<div class="tf-spacing-20"></div>
<div class="tf-spacing-20"></div>
<div class="transfer-content">
    <div class="tf-container">
        <form action="{{ url('/cuti') }}">
            <div class="row">
                <div class="col-5">
                    <input type="datetime" name="mulai" placeholder="Mulai" id="mulai" value="{{ request('mulai') }}">
                </div>
                <div class="col-5">
                    <input type="datetime" name="akhir" placeholder="Akhir" id="akhir" value="{{ request('akhir') }}">
                </div>
                <div class="col-1">
                    <button type="submit" id="search" class="form-control btn" style="width: 25px"><i
                            class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="tf-spacing-20"></div>
<div class="transfer-content">
    <div class="tf-container">
        <table class="table table-striped" id="tablePayroll">
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
                @foreach ($data_cuti_user as $dcu)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dcu->User->name }}</td>
                    <td>{{ $dcu->nama_cuti }}</td>
                    <td>{{ $dcu->tanggal_mulai}}</td>
                    <td>{{ $dcu->tanggal_akhir}}</td>
                    <td>{{ $dcu->alasan_cuti}}</td>
                    <td>
                        <img cursor:pointer; src="{{ url('storage/'.$dcu->foto_cuti) }}" style="width:80px" alt="Image"
                            onclick="showModal(this, '{{ url('storage/'.$dcu->foto_cuti) }}')">
                    </td>
                    <td>
                        @if($dcu->status_cuti == "Diterima")
                        {{ $dcu->status_cuti }}
                        @elseif($dcu->status_cuti == "Ditolak")
                        {{ $dcu->status_cuti }}
                        @else
                        {{ $dcu->status_cuti }}
                        @endif
                    </td>
                    <td>{{ $dcu->catatan }}</td>
                    <td>
                        @if($dcu->status_cuti == "Diterima")
                        <a href="{{ url('/cuti/'.$dcu->id.'/download') }}" target="_blank"><i style="color:blue"
                                class="fa fa-solid fa-print"></i></a>
                        @endif

                        @if($dcu->status_cuti == "Diterima")
                        Sudah Approve
                        @else
                        <a href="{{ url('/cuti/edit/'.$dcu->id) }}" class="btn btn-sm btn-warning btn-circle"><i
                                class="fa fa-solid fa-edit"></i></a>
                        @endif

                        @if($dcu->status_cuti == "Diterima")
                        Sudah Approve
                        @else
                        <form action="{{ url('/cuti/delete/'.$dcu->id) }}" method="post" class="d-inline">
                            @method('delete')
                            @csrf
                            <button class="btn btn-sm btn-danger btn-circle delete-btn" style="width: 30px"><i
                                    class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end mr-4">
        {{ $data_cuti_user->links() }}
    </div>
</div>
<br>
<br>
<br>
<br>
@push('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('.select2').select2();
});
</script>
@endpush
@endsection