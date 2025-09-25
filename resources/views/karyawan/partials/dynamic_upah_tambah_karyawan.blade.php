<div class="col mb-4">
    <h3 style="color: blue">Dinamik Penjumlahan Gaji</h3>
</div>
@if(isset($data_dynamic_upah))
    @foreach($data_dynamic_upah->chunk(2) as $chunk)
        <div class="form-row">
            @foreach($chunk as $dynamicUpah)
                @php
                    $namaDynamicUpah = str_replace(' ', '-', $dynamicUpah->nama);
                @endphp
                <div class="col mb-4">
                    <label for="{{ $namaDynamicUpah }}">{{ ucfirst($dynamicUpah->nama) }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span>Rp.</span>
                            </div>
                        </div>
                        <input type="text" class="form-control money @error('{{ $namaDynamicUpah }}') is-invalid @enderror" id="{{ $namaDynamicUpah }}" name="{{ $namaDynamicUpah }}" value="{{ number_format($dynamicUpah->jumlah, 0, ',', '.') }}" disabled>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span> / {{ $dynamicUpah->keterangan }}</span>
                            </div>
                        </div>
                        @error('{{ $namaDynamicUpah }}')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
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
