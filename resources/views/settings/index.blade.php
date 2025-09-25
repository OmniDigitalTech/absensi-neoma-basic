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
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <form method="post" class="p-4" action="{{ url('/settings/store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="float-left">Nama Perusahaan</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        autofocus value="{{ old('name', $data->name) }}">
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="alamat" class="float-left">Alamat</label>
                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                        name="alamat" autofocus value="{{ old('alamat', $data->alamat) }}">
                    @error('alamat')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone" class="float-left">No HP</label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone"
                        name="phone" autofocus value="{{ old('phone', $data->phone) }}">
                    @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="float-left">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" autofocus value="{{ old('email', $data->email) }}">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="logo" class="form-label">Logo</label>
                    @if($data->logo)
                        <span>Sudah diupload. Preview: </span>
                        <a href="javascript:void(0)" onclick="showModal(this, '{{ file_exists(public_path('storage/' . $data->logo)) ? url('storage/' . $data->logo) : url('assets/img/neoma_logo.jpg') }}')"><span class="fa fa-eye"></span></a>
                    @else
                        <span>Belum diupload. Silahkan upload dibawah:</span>
                    @endif
                    <input class="form-control @error('logo') is-invalid @enderror" type="file" id="logo" name="logo">
                    @error('logo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="bpjsKesehatan" class="form-label">Pakai BPJS Kesehatan?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bpjs_kesehatan" id="YesRadioButtonKesehatan" value="ya" {{ $data->bpjs_kesehatan == 'ya' ? 'checked' : '' }}>
                        <label class="form-check-label" for="YesRadioButtonKesehatan">
                            Ya, Pakai
                        </label>
                    </div>
                    <div class="form-group m-l-25" id="bpjsKesehatanKontrak" style="display: none;">
                        <label for="bpjsKesehatanKontrak" class="form-label">Pegawai Kontrak Pakai BPJS Kesehatan?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bpjs_kesehatan_kontrak" id="YesRadioButtonKontrak" value="ya" {{ $data->bpjs_kesehatan_kontrak == 'ya' ? 'checked' : '' }}>
                            <label class="form-check-label" for="YesRadioButtonKontrak">
                                Ya, Pakai
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="bpjs_kesehatan_kontrak" id="NoRadioButtonKontrak" value="tidak" {{ $data->bpjs_kesehatan_kontrak == 'tidak' ? 'checked' : '' }}>
                            <label class="form-check-label" for="NoRadioButtonKontrak">
                                Tidak Pakai
                            </label>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bpjs_kesehatan" id="NoRadioButtonKesehatan" value="tidak" {{ $data->bpjs_kesehatan == 'tidak' ? 'checked' : '' }}>
                        <label class="form-check-label" for="NoRadioButtonKesehatan">
                            Tidak Pakai
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bpjsKetenagakerjaan" class="form-label">Pakai BPJS Ketengakerjaan?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bpjs_ketenagakerjaan" id="YesRadioButtonKetenagakerjaan" value="ya" {{ $data->bpjs_ketenagakerjaan == 'ya' ? 'checked' : '' }}>
                        <label class="form-check-label" for="YesRadioButtonKetenagakerjaan">
                            Ya, Pakai
                        </label>
                    </div>
                    <div class="form-group m-l-25" id="jenisBpjsKetenagakerjaan" style="display: none;">
                        <label for="jenisBpjsKetenagakerjaan" class="form-label">Pilih Jenis BPJS Ketenagakerjaan:</label>
                        @php
                            $nameAttribute = [
                                'bpjs_ketenagakerjaan_jht',
                                'bpjs_ketenagakerjaan_jp',
                                'bpjs_ketenagakerjaan_jkm',
                                'bpjs_ketenagakerjaan_jkp'
                            ]
                        @endphp
                        @foreach($data_ketenagakerjaan as $index => $item)
                            <div class="form-check">
{{--                                @php--}}
{{--                                    $nameAttribute = 'bpjs_ketenagakerjaan_' . implode('', array_map(function($word) {--}}
{{--                                        return strtolower($word[0]);--}}
{{--                                    }, explode(' ', $item->name)));--}}
{{--                                @endphp--}}
                                <input class="form-check-input" type="checkbox" value="{{ $item->id }}" name="{{ $nameAttribute[$index] }}" id="{{ $item->id }}" {{ $data->{$nameAttribute[$index]} !== null ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="ya" name="jkkCheckbox" id="jkkCheckbox" {{ $data->bpjs_ketenagakerjaan_jkk != null ? 'checked' : '' }}>
                                <label class="form-check-label" for="jkkCheckbox">
                                    Jaminan Kecelakaan Kerja (JKK)
                                </label>
                            </div>
                            <div class="form-group m-l-45" id="jenisJkk" style="display: none;">
                                @foreach($data_ketenagakerjaan_jkk as $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bpjs_ketenagakerjaan_jkk" id="{{ str_replace(' ', '-', $item->name) }}" value="{{ $item->id }}" {{ $data->bpjs_ketenagakerjaan_jkk == $item->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $item->id }}">
                                            {{ $item->name }} ({{  $item->nominal }}%)
                                        </label>
                                    </div>
                                @endforeach
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="radio" name="jenisJkkCheckbox" id="tingkatResiko1" value="0.870">--}}
{{--                                    <label class="form-check-label" for="tingkatResiko1">--}}
{{--                                        Sangat Tinggi (0.870%)--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="radio" name="jenisJkkCheckbox" id="tingkatResiko2" value="0.635">--}}
{{--                                    <label class="form-check-label" for="tingkatResiko2">--}}
{{--                                        Tinggi (0.635%)--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="radio" name="jenisJkkCheckbox" id="tingkatResiko3" value="0.445">--}}
{{--                                    <label class="form-check-label" for="tingkatResiko3">--}}
{{--                                        Sedang (0.445%)--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="radio" name="jenisJkkCheckbox" id="tingkatResiko4" value="0.270">--}}
{{--                                    <label class="form-check-label" for="tingkatResiko4">--}}
{{--                                        Rendah (0.270%)--}}
{{--                                    </label>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bpjs_ketenagakerjaan" id="NoRadioButtonKetenagakerjaan" value="tidak" {{ $data->bpjs_ketenagakerjaan == 'tidak' ? 'checked' : '' }}>
                        <label class="form-check-label" for="NoRadioButtonKetenagakerjaan">
                            Tidak Pakai
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </form>
        </div>
    </div>
</div>
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yesRadioButton = document.getElementById('YesRadioButtonKesehatan');
            const noRadioButton = document.getElementById('NoRadioButtonKesehatan');
            const noRadioButtonKontrak = document.getElementById('NoRadioButtonKontrak');
            const bpjsKesehatanKontrak = document.getElementById('bpjsKesehatanKontrak');

            yesRadioButton.addEventListener('change', function() {
                if (yesRadioButton.checked) {
                    bpjsKesehatanKontrak.style.display = 'block';
                }
            });

            noRadioButton.addEventListener('change', function() {
                if (noRadioButton.checked) {
                    bpjsKesehatanKontrak.style.display = 'none';
                    noRadioButtonKontrak.checked = true; // Automatically check the kontrak "No" radio button
                }
            });

            // Initial check
            if (yesRadioButton.checked) {
                bpjsKesehatanKontrak.style.display = 'block';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yesRadioButtonKetenagakerjaan = document.getElementById('YesRadioButtonKetenagakerjaan');
            const noRadioButtonKetenagakerjaan = document.getElementById('NoRadioButtonKetenagakerjaan');
            const jenisBpjsKetenagakerjaan = document.getElementById('jenisBpjsKetenagakerjaan');
            const jkkCheckbox = document.getElementById('jkkCheckbox');
            const jenisJkk = document.getElementById('jenisJkk');

            yesRadioButtonKetenagakerjaan.addEventListener('change', function() {
                if (yesRadioButtonKetenagakerjaan.checked) {
                    jenisBpjsKetenagakerjaan.style.display = 'block';
                }
            });

            noRadioButtonKetenagakerjaan.addEventListener('change', function() {
                if (noRadioButtonKetenagakerjaan.checked) {
                    jenisBpjsKetenagakerjaan.style.display = 'none';
                    jenisJkk.style.display = 'none';
                    jkkCheckbox.checked = false;

                    // Uncheck all checkboxes inside jenisBpjsKetenagakerjaan
                    const checkboxes = jenisBpjsKetenagakerjaan.querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Uncheck all radio buttons inside jenisJkk
                    const radioButtons = jenisJkk.querySelectorAll('input[type="radio"]');
                    radioButtons.forEach(radioButton => {
                        radioButton.checked = false;
                    });
                }
            });

            jkkCheckbox.addEventListener('change', function() {
                if (jkkCheckbox.checked) {
                    jenisJkk.style.display = 'block';
                } else {
                    jenisJkk.style.display = 'none';
                }
            });

            // Initial check
            if (yesRadioButtonKetenagakerjaan.checked) {
                jenisBpjsKetenagakerjaan.style.display = 'block';
            }
            if (jkkCheckbox.checked) {
                jenisJkk.style.display = 'block';
            }
        });
    </script>
@endpush
@endsection
