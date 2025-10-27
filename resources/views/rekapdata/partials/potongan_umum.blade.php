<div class="col mb-4">
    <h4 style="color: blue">Umum</h4>
</div>
@if(!empty($data_deduksi))
    @foreach($data_deduksi->chunk(2) as $chunk)
        <div class="form-row">
            @foreach($chunk as $deduksi)
                @php
                    $namaUmum = str_replace(' ', '-', $deduksi->nama);
                @endphp
                <div class="col mb-4">
                    <div class="card p-4">
                        <label for="jumlah_{{ $namaUmum }}">{{ ucfirst($deduksi->nama) }}</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control @error($namaUmum) is-invalid @enderror" name="jumlah_{{ $namaUmum }}" value="{{ old($namaUmum, $namaUmum) }}" id="jumlah_{{ $namaUmum }}" style="background-color: orange">
                            <div class="input-group-text">
                                <span>/ Kali</span>
                            </div>
                            @error($namaUmum)
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
                            <input type="text" class="form-control @error($namaUmum) is-invalid @enderror" name="{{ $namaUmum }}" id="{{ $namaUmum }}"
                                   value="@if(fmod($deduksi->nominal, 1) === 0){{ number_format($deduksi->nominal, 0, ',', '.') }}@else{{ number_format($deduksi->nominal, 2, ',', '.') }}@endif" disabled>
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
                        <input type="hidden" name="total_izin" id="total_izin" value="{{ old('total_izin') }}">
                    </div>
{{--                    <label for="{{ $namaUmum }}">{{ ucfirst($deduksi->nama) }}</label>--}}
{{--                    <div class="input-group mb-3">--}}
{{--                        <div class="input-group-append">--}}
{{--                            <div class="input-group-text">--}}
{{--                                <span> Rp. </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <input type="text" class="form-control @error($namaUmum) is-invalid @enderror" name="{{ $namaUmum }}" id="{{ $namaUmum }}"--}}
{{--                               value="@if(fmod($deduksi->nominal, 1) == 0){{ number_format($deduksi->nominal, 0, ',', '.') }}@else{{ number_format($deduksi->nominal, 2, ',', '.') }}@endif" disabled>--}}
{{--                        <div class="input-group-append">--}}
{{--                            <div class="input-group-text">--}}
{{--                                <span> / {{ $deduksi->keterangan }}</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        @error($namaUmum)--}}
{{--                        <div class="invalid-feedback">--}}
{{--                            {{ $message }}--}}
{{--                        </div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                </div>
            @endforeach
        </div>
    @endforeach
@endif
