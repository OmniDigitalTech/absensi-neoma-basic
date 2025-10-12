<div class="col mb-4">
    <h4 style="color: blue">BPJS Kesehatan & Ketenagakerjaan</h4>
</div>
@if(!empty($data_bpjs_kesehatan) || !empty($data_bpjs_ketenagakerjaan) || !empty($data_bpjs_ketenagakerjaan_jkk))
    @if(!empty($data_bpjs_kesehatan))
        <div class="form-row">
            <div class="col mb-4">
                <div class="card p-4">
                    <label for="potongan_bpjs_kesehatan">{{ $data_bpjs_kesehatan[0]['name'] }}</label>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span> Rp. </span>
                            </div>
                        </div>
                        <input type="text" class="form-control money @error('bpjs_kesehatan') is-invalid @enderror"
                               id="potongan_bpjs_kesehatan" name="potongan_bpjs_kesehatan"
                               value="{{ old('bpjs_kesehatan', number_format($data_bpjs_kesehatan[0]['nominal'], 0, ',', '.')) }}" readonly>
                        <div class="input-group-text">
                            <span>Potongan</span>
                        </div>
                        @error('bpjs_kesehatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(!empty($data_bpjs_ketenagakerjaan))
        @foreach($data_bpjs_ketenagakerjaan->chunk(2) as $chunk)
            <div class="form-row">
                @foreach($chunk as $deduksiBpjsKetenagakerjaan)
                    @php
                        $namaBpjsKetenagakerjaan = str_replace(' ', '_', $deduksiBpjsKetenagakerjaan->name);
                    @endphp
                    <div class="col mb-4">
                        <div class="card p-4">
                            <label for="{{ $namaBpjsKetenagakerjaan }}">{{ $deduksiBpjsKetenagakerjaan->name }}</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control @error($namaBpjsKetenagakerjaan) is-invalid @enderror"
                                       name="{{ $namaBpjsKetenagakerjaan }}" id="{{ $namaBpjsKetenagakerjaan }}" style="background-color: orange"
                                       value="{{ old($namaBpjsKetenagakerjaan, number_format($deduksiBpjsKetenagakerjaan->nominal, 2, ',', '.')) }}" readonly>
                                <div class="input-group-text">
                                    <span>%</span>
                                </div>
                                @error($namaBpjsKetenagakerjaan)
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span> Rp. </span>
                                    </div>
                                </div>
                                <input type="text" class="form-control money @error($namaBpjsKetenagakerjaan) is-invalid @enderror"
                                       id="potongan_{{ $namaBpjsKetenagakerjaan }}" name="potongan_{{ $namaBpjsKetenagakerjaan }}"
                                       value="{{ old($namaBpjsKetenagakerjaan, number_format($deduksiBpjsKetenagakerjaan->nilai_potongan, 0, ',', '.')) }}" readonly>
                                <div class="input-group-text">
                                    <span>Potongan</span>
                                </div>
                                @error($namaBpjsKetenagakerjaan)
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <input type="hidden" name="id_{{ $namaBpjsKetenagakerjaan }}" value="{{ $deduksiBpjsKetenagakerjaan->id }}">
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
    @if(!empty($data_bpjs_ketenagakerjaan_jkk))
        <div class="form-row">
            @foreach($data_bpjs_ketenagakerjaan_jkk as $deduksiBpjsKetenagakerjaanJkk)
                @php
                    $namaBpjsKetenagakerjaanJkk = str_replace(' ', '_', $deduksiBpjsKetenagakerjaanJkk->name);
                @endphp
                <div class="col mb-4">
                    <div class="card p-4">
                        <label for="bpjs_ketenagakerjaan_jkk_{{ $namaBpjsKetenagakerjaanJkk }}">Jaminan Kecelakaan Kerja - {{ $deduksiBpjsKetenagakerjaanJkk->name }}</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control @error($namaBpjsKetenagakerjaanJkk) is-invalid @enderror"
                                   name="bpjs_ketenagakerjaan_jkk_{{ $namaBpjsKetenagakerjaanJkk }}" id="bpjs_ketenagakerjaan_jkk_{{ $namaBpjsKetenagakerjaanJkk }}" style="background-color: orange"
                                   value="{{ old($namaBpjsKetenagakerjaanJkk, number_format($deduksiBpjsKetenagakerjaanJkk->nominal, 2, ',', '.')) }}" readonly>
                            <div class="input-group-text">
                                <span>%</span>
                            </div>
                            @error($namaBpjsKetenagakerjaanJkk)
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span> Rp. </span>
                                </div>
                            </div>
                            <input type="text" class="form-control money @error($namaBpjsKetenagakerjaanJkk) is-invalid @enderror"
                                   id="potongan_Jaminan_Kecelakaan_Kerja" name="potongan_Jaminan_Kecelakaan_Kerja"
                                   value="{{ old($namaBpjsKetenagakerjaanJkk, number_format($deduksiBpjsKetenagakerjaanJkk->nilai_potongan, 0, ',', '.')) }}" readonly>
                            <div class="input-group-text">
                                <span>Potongan</span>
                            </div>
                            @error($namaBpjsKetenagakerjaanJkk)
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <input type="hidden" name="id_Jaminan_Kecelakaan_Kerja" value="{{ $deduksiBpjsKetenagakerjaanJkk->id }}">
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@else
    <div class="form-row">
        <div class="col mb-4 text-center">
            <h4>Tidak Ada Pengurangan BPJS Kesehatan & Ketenagakerjaan</h4>
        </div>
    </div>
@endif
