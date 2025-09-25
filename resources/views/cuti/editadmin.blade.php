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
                        <a href="{{ url('/data-cuti') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <form method="post" action="{{ url('/data-cuti/edit-proses/'.$data_cuti_karyawan->id) }}" class="p-4">
                    @method('put')
                    @csrf
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="user_id">Nama Pegawai</label>
                            <input type="text" disabled class="form-control" value="{{ $data_cuti_karyawan->User->name }}" id="user_id">
                        </div>
                        <div class="col mb-4">
                            <label for="nama_cuti">Nama Cuti</label>
                            <input type="text" class="form-control" value="{{ $data_cuti_karyawan->nama_cuti }}" id="nama_cuti" disabled>
                            <input type="hidden" name="nama_cuti" value="{{ $data_cuti_karyawan->nama_cuti }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="datetime" class="form-control @error('tanggal mulai') is-invalid @enderror" name="tanggal_mulai" id="tanggal_mulai" value="{{ $data_cuti_karyawan->tanggal_mulai }}">
                            @error('tanggal mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="datetime" class="form-control @error('tanggal akhir') is-invalid @enderror" name="tanggal_akhir" id="tanggal_akhir" value="{{ $data_cuti_karyawan->tanggal_akhir }}">
                            @error('tanggal akhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-4">
                            <label for="shift_id">Shift</label>
                                <select id="shift_id" name="shift_id" class="form-control selectpicker" required>
                                    <option value="">Pilih Shift</option>
                                    @foreach ($data_shift as $ds)
                                        @if($data_shift_karyawan)
                                            @if($data_shift_karyawan->shift_id != null && $data_shift_karyawan->shift_id == $ds->id)
                                                <option value="{{ $ds->id }}" selected>
                                                    {{ $data_shift_karyawan->Shift->nama_shift }} ({{$data_shift_karyawan->Shift->jam_masuk}} - {{$data_shift_karyawan->Shift->jam_keluar}})
                                                </option>
                                            @else
                                                <option value="{{ $ds->id }}">{{ $ds->nama_shift }} ({{$ds->jam_masuk}} - {{$ds->jam_keluar}})</option>
                                            @endif
                                        @else
                                            <option value="{{ $ds->id }}">{{ $ds->nama_shift }} ({{$ds->jam_masuk}} - {{$ds->jam_keluar}})</option>
                                        @endif
                                    @endforeach
                                </select>
{{--                            @endif--}}
                        </div>
                        <div class="col mb-4">
                            <label for="alasan_cuti">Alasan Cuti</label>
                            <input type="text" disabled class="form-control" value="{{ $data_cuti_karyawan->alasan_cuti }}">
                        </div>
                    </div>
                    <div class="form-row">
                        @php
                            $status_cuti = array(
                            [
                                "status_cuti" => "Pending"
                            ],
                            [
                                "status_cuti" => "Ditolak"
                            ],
                            [
                                "status_cuti" => "Diterima"
                            ]);
                        @endphp
                        <div class="col mb-4">
                            <label for="status_cuti">Status Cuti</label>
                            <select name="status_cuti" class="form-control @error('status_cuti') is-invalid @enderror selectpicker" data-live-search="true" id="status_cuti">
                                @foreach ($status_cuti as $sc)
                                    @if(old('status_cuti', $data_cuti_karyawan->status_cuti) == $sc["status_cuti"])
                                        <option value="{{ $sc["status_cuti"] }}" selected>{{ $sc["status_cuti"] }}</option>
                                    @else
                                        <option value="{{ $sc["status_cuti"] }}">{{ $sc["status_cuti"] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('status_cuti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="catatan">Catatan</label>
                            <input type="text" class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan" value="{{ old('catatan', $data_cuti_karyawan->catatan) }}" required>
                            @error('catatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="jam_absen">
                    <input type="hidden" name="telat">
                    <input type="hidden" name="lat_absen">
                    <input type="hidden" name="long_absen">
                    <input type="hidden" name="jarak_masuk">
                    <input type="hidden" name="foto_jam_absen">
                    <input type="hidden" name="jam_pulang">
                    <input type="hidden" name="pulang_cepat">
                    <input type="hidden" name="foto_jam_pulang">
                    <input type="hidden" name="foto_jam_pulang">
                    <input type="hidden" name="lat_pulang">
                    <input type="hidden" name="long_pulang">
                    <input type="hidden" name="jarak_pulang">
                    <input type="hidden" name="status_absen">
                    <input type="hidden" name="izin_cuti">
                    <input type="hidden" name="izin_lainnya">
                    <input type="hidden" name="izin_telat">
                    <input type="hidden" name="izin_pulang_cepat">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
            </div>
        </div>
    </div>
@endsection
