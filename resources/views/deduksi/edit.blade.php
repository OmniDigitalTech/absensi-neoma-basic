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
                        <a href="{{ url('/deduksi') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <form method="post" action="{{ url('/deduksi/proses-edit-deduksi/'.$data_deduksi->id) }}" class="p-4">
                    @method('put')
                    @csrf
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="nama">Nama Deduksi</label>
                            <input type="text" class="form-control" value="{{ $data_deduksi->nama }}" name="nama" id="nama">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="nominal">Nominal</label>
                            <input type="text" class="form-control @error('nominal') is-invalid @enderror" name="nominal" id="nominal" value="{{ old('nominal', $data_deduksi->nominal) }}" required>
                            @error('nominal')
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
