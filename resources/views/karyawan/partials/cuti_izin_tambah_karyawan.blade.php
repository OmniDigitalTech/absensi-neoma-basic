<div class="col mb-4">
    <h3 style="color: blue">Cuti & Izin</h3>
</div>
@if(isset($data_cuti_izin))
    @foreach($data_cuti_izin->chunk(2) as $chunk)
        <div class="form-row">
            @foreach($chunk as $cuti)
                @php
                    $namaCutiIzin = str_replace(' ', '-', $cuti->nama);
                @endphp
                <div class="col mb-4">
                    <label for="{{ $namaCutiIzin }}">{{ ucfirst($cuti->nama) }}</label>
                    <input type="number" class="form-control @error('{{ $namaCutiIzin }}') is-invalid @enderror" id="{{ $namaCutiIzin }}" name="{{ $namaCutiIzin }}" value="{{ $cuti->jumlah }}" disabled>
                    @error('{{ $namaCutiIzin }}')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @endforeach
        </div>
    @endforeach
@else
    <div class="form-row">
        <div class="col mb-4 text-center">
            <h4>Pilih Golongan Terlebih Dahulu</h4>
        </div>
    </div>
@endif
