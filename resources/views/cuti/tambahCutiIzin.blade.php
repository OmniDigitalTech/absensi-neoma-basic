@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-md-12 m project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 p-0 d-flex mt-2">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">
                        <a href="{{ url('/data-cuti/cuti-izin') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <form method="post" action="{{ url('/data-cuti/proses-tambah-cuti-izin') }}" class="p-4">
                    @method('post')
                    @csrf
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="nama">Nama Cuti</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" name="jumlah" id="jumlah" required>
                            @error('jumlah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
