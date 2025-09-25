@extends('templates.dashboard')
@section('isi')
    <center>
        <div class="container-fluid">
            <div class="card col-lg-4">
                <div class="p-4">
                    <form method="post" action="{{ url('/dynamic-upah/tambah-proses') }}">
                        @csrf
                        <div class="form-group">
                            <label for="golongan_id" class="float-left">Golongan</label>
                            <select name="golongan_id" id="golongan_id" class="form-control selectpicker" data-live-search="true">
                                <option value="">Pilih Golongan</option>
                                @foreach ($data_golongan as $dg)
                                    @if(old('golongan_id') == $dg->id)
                                        <option value="{{ $dg->id }}" selected>{{ $dg->name }}</option>
                                    @else
                                        <option value="{{ $dg->id }}">{{ $dg->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('golongan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama" class="float-left">Nama Upah</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" autofocus value="{{ old('nama') }}">
                            @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jumlah" class="float-left">Jumlah</label>
                            <input type="text" class="form-control money @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" autofocus value="{{ old('jumlah') }}">
                            @error('jumlah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="float-left">Keterangan</label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" autofocus value="{{ old('keterangan') }}">
                            @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-danger float-left">Back</a>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </center>
    <br>
    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function(){
                $('.money').mask('000,000,000,000,000.00', {
                    reverse: true
                });
            });
        </script>
    @endpush
@endsection
