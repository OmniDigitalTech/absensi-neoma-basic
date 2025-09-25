<div class="col mb-4">
    <h3 style="color: blue">Penjumlahan Gaji</h3>
</div>
@if(isset($data_upah))
    <div class="form-row">
        <div class="col mb-4">
            <label for="gaji_pokok">Gaji Pokok</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control money @error('gaji_pokok') is-invalid @enderror" name="gaji_pokok" value="{{ old('gaji_pokok') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span>/ Bulan</span>
                    </div>
                </div>
                @error('gaji_pokok')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="col mb-4">
            <label for="makan_transport">Makan Dan Transport</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control money @error('makan_transport') is-invalid @enderror" name="makan_transport" value="{{ old('makan_transport') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span>/ Bulan</span>
                    </div>
                </div>
                @error('makan_transport')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col mb-4">
            <label for="lembur">Lembur</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control money @error('lembur') is-invalid @enderror" name="lembur" value="{{ old('lembur') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span>/ Jam</span>
                    </div>
                </div>
                @error('lembur')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="col mb-4">
            <label for="kehadiran">100% Kehadiran</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control money @error('kehadiran') is-invalid @enderror" name="kehadiran" value="{{ old('kehadiran') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span>/ Bulan</span>
                    </div>
                </div>
                @error('kehadiran')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col mb-4">
            <label for="thr">THR</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control money @error('thr') is-invalid @enderror" name="thr" value="{{ old('thr') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span>/ Bulan</span>
                    </div>
                </div>
                @error('thr')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
        <div class="col mb-4">
            <label for="bonus">Bonus</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control money @error('bonus') is-invalid @enderror" name="bonus" value="{{ old('bonus') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span>/ Bulan</span>
                    </div>
                </div>
                @error('bonus')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>
@else
    <div class="form-row">
        <div class="col mb-4 text-center">
            <h4>Pilih Golongan Terlebih Dahulu</h4>
        </div>
    </div>
@endif
