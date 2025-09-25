@extends('templates.dashboard')
@section('isi')
    <center>
        <div class="container-fluid">
            <div class="card col-lg-4">
                <div class="p-4">
                    <form method="post" action="{{ url('/tunjangan/'.$data->id.'/update') }}">
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
                                <label for="tunjangan_makan" class="float-left">Tunjangan Makan</label>
                                <input type="text" class="form-control money @error('tunjangan_makan') is-invalid @enderror" id="tunjangan_makan" name="tunjangan_makan" autofocus value="{{ old('tunjangan_makan', number_format($data->tunjangan_makan, 2)) }}">
                                @error('tunjangan_makan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tunjangan_transport" class="float-left">Tunjangan Transport</label>
                                <input type="text" class="form-control money @error('tunjangan_transport') is-invalid @enderror" id="tunjangan_transport" name="tunjangan_transport" autofocus value="{{ old('tunjangan_transport', number_format($data->tunjangan_transport, 2)) }}">
                                @error('tunjangan_transport')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="thr" class="float-left">Tunjangan Hari Raya</label>
                                <input type="text" class="form-control money @error('thr') is-invalid @enderror" id="thr" name="thr" autofocus value="{{ old('thr', number_format($data->thr, 2)) }}">
                                @error('thr')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bonus" class="float-left">Bonus</label>
                                <input type="text" class="form-control money @error('bonus') is-invalid @enderror" id="bonus" name="bonus" autofocus value="{{ old('bonus', number_format($data->bonus, 2)) }}">
                                @error('bonus')
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
