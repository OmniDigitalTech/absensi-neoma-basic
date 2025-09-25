@extends('templates.dashboard')
@section('isi')
    <center>
        <div class="container-fluid">
            <div class="card col-lg-4">
                <div class="p-4">
                    <form method="post" action="{{ url('/upah/update/'.$data->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="golongan_id" class="float-left">Golongan</label>
                            <select name="golongan_id" id="golongan_id" class="form-control selectpicker" data-live-search="true">
                                <option value="">Pilih Golongan</option>
                                @foreach ($data_golongan as $dg)
                                    @if(old('golongan_id', $data->golongan_id) == $dg->id)
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
                            <label for="gaji_pokok" class="float-left">Gaji Pokok</label>
                            <input type="text" class="form-control money @error('gaji_pokok') is-invalid @enderror" id="gaji_pokok" name="gaji_pokok" autofocus value="{{ old('gaji_pokok', number_format($data->gaji_pokok, 2)) }}">
                            @error('gaji_pokok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kehadiran" class="float-left">Kehadiran</label>
                            <input type="text" class="form-control money @error('kehadiran') is-invalid @enderror" id="kehadiran" name="kehadiran" autofocus value="{{ old('kehadiran', number_format($data->kehadiran, 2)) }}">
                            @error('kehadiran')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lembur" class="float-left">Lembur</label>
                            <input type="text" class="form-control money @error('lembur') is-invalid @enderror" id="lembur" name="lembur" autofocus value="{{ old('lembur', number_format($data->lembur, 2)) }}">
                            @error('lembur')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="oncall" class="float-left">oncall</label>
                            <input type="text" class="form-control money @error('oncall') is-invalid @enderror" id="oncall" name="oncall" autofocus value="{{ old('oncall', number_format($data->oncall, 2)) }}">
                            @error('oncall')
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
