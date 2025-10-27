<div class="col mb-4">
    <h3 style="color: blue">Pengurangan Gaji</h3>
</div>
@if(!empty($data_deduksi) || !empty($data_bpjs_kesehatan) || !empty($data_bpjs_ketenagakerjaan) || !empty($data_bpjs_ketenagakerjaan_jkk))
    <div class="col mb-4">
        <h5 style="color: blue">UMUM</h5>
    </div>
    @if($data_deduksi !== [])
        @foreach($data_deduksi->chunk(2) as $chunk)
            <div class="form-row">
                @foreach($chunk as $deduksi)
                    @php
                        $namaUmum = str_replace(' ', '-', $deduksi->nama);
                    @endphp
                    <div class="col mb-4">
                        <label for="{{ $namaUmum }}">{{ ucfirst($deduksi->nama) }}</label>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span> Rp. </span>
                                </div>
                            </div>
                            <input type="text" class="form-control @error($namaUmum) is-invalid @enderror"
                                   name="{{ $namaUmum }}" id="{{ $namaUmum }}"
                                   value="@if(fmod($deduksi->nominal, 1) == 0){{ number_format($deduksi->nominal, 0, ',', '.') }}@else{{ number_format($deduksi->nominal, 2, ',', '.') }}@endif"
                                   disabled>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span> / {{ $deduksi->keterangan }}</span>
                                </div>
                            </div>
                            @error($namaUmum)
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
                <h4>Tidak Ada Pengurangan Gaji Umum</h4>
            </div>
        </div>
    @endif
    <div class="col mb-4">
        <h5 style="color: blue">BPJS Kesehatan</h5>
    </div>
    <div class="form-row">
        @if($data_bpjs_kesehatan !== [])
            @foreach($data_bpjs_kesehatan as $deduksiBpjsKesehatan)
                @php
                    $namaBpjsKesehatan = str_replace(' ', '-', $deduksiBpjsKesehatan->name);
                @endphp
                <div class="col mb-4">
                    <label for="{{ $namaBpjsKesehatan }}">{{ ucfirst($deduksiBpjsKesehatan->name) }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span> Rp. </span>
                            </div>
                        </div>
                        <input type="text" class="form-control @error($namaBpjsKesehatan) is-invalid @enderror"
                               name="{{ $namaBpjsKesehatan }}" id="{{ $namaBpjsKesehatan }}"
                               value="@if(fmod($deduksiBpjsKesehatan->nominal, 1) == 0){{ number_format($deduksiBpjsKesehatan->nominal, 0, ',', '.') }}@else{{ number_format($deduksiBpjsKesehatan->nominal, 2, ',', '.') }}@endif"
                               disabled>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span> / {{ $deduksiBpjsKesehatan->keterangan }}</span>
                            </div>
                        </div>
                        @error($namaBpjsKesehatan)
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            @endforeach
        @else
            <div class="col mb-4 text-center">
                <h4>Tidak Ada Pengurangan Gaji Bpjs Kesehatan</h4>
            </div>
        @endif
    </div>
    <div class="col mb-4">
        <h5 style="color: blue">BPJS Ketenagakerjaan</h5>
    </div>
    @if($data_bpjs_ketenagakerjaan !== [])
        @foreach($data_bpjs_ketenagakerjaan->chunk(2) as $chunk)
            <div class="form-row">
                @foreach($chunk as $deduksiBpjsKetenagakerjaan)
                    @php
                        $namaBpjsKetenagakerjaan = str_replace(' ', '-', $deduksiBpjsKetenagakerjaan->name);
                    @endphp
                    <div class="col mb-4">
                        <label
                            for="{{ $namaBpjsKetenagakerjaan }}">{{ ucfirst($deduksiBpjsKetenagakerjaan->name) }}</label>
                        <div class="input-group mb-3">
                            <input type="text"
                                   class="form-control @error($namaBpjsKetenagakerjaan) is-invalid @enderror"
                                   name="{{ $namaBpjsKetenagakerjaan }}" id="{{ $namaBpjsKetenagakerjaan }}"
                                   value="{{ number_format($deduksiBpjsKetenagakerjaan->nominal, 2, ',', '.') }} %"
                                   disabled>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span> / {{ $deduksiBpjsKetenagakerjaan->keterangan }}</span>
                                </div>
                            </div>
                            @error($namaBpjsKetenagakerjaan)
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        <div class="form-row">
            @if(!empty($data_bpjs_ketenagakerjaan_jkk))
                @foreach($data_bpjs_ketenagakerjaan_jkk as $deduksiBpjsKetenagakerjaanJkk)
                    @php
                        $namaBpjsKetenagakerjaanJkk = str_replace(' ', '-', $deduksiBpjsKetenagakerjaanJkk->name);
                    @endphp
                    <div class="col mb-4">
                        <label for="{{ $namaBpjsKetenagakerjaanJkk }}">Jaminan Kecelakaan Kerja
                            - {{ ucfirst($deduksiBpjsKetenagakerjaanJkk->name) }}</label>
                        <div class="input-group mb-3">
                            <input type="text"
                                   class="form-control @error($namaBpjsKetenagakerjaanJkk) is-invalid @enderror"
                                   name="{{ $namaBpjsKetenagakerjaanJkk }}" id="{{ $namaBpjsKetenagakerjaanJkk }}"
                                   value="{{ number_format($deduksiBpjsKetenagakerjaanJkk->nominal, 2, ',', '.') }} %"
                                   disabled>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span> / {{ $deduksiBpjsKetenagakerjaanJkk->keterangan }}</span>
                                </div>
                            </div>
                            @error($namaBpjsKetenagakerjaanJkk)
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @else
        <div class="form-row">
            <div class="col mb-4 text-center">
                <h4>Tidak Ada Pengurangan Gaji Bpjs Ketenagakerjaan</h4>
            </div>
        </div>
    @endif
@else
    <div class="form-row">
        <div class="col mb-4 text-center">
            <h4>Pilih Golongan Terlebih Dahulu</h4>
        </div>
    </div>
@endif
