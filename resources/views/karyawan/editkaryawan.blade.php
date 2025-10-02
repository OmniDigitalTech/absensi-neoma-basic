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
                    <a href="{{ url('/pegawai') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="display: flex; flex-direction: column;">
        <div class="col" style="flex: none">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if(is_null($karyawan->foto_karyawan))
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ url('assets/img/foto_default.jpg') }}" alt="User profile picture">
                        @else
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ url('storage/'.$karyawan->foto_karyawan) }}" alt="User profile picture">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $karyawan->name }}</h3>

                    <p class="text-muted text-center">{{ $karyawan->Jabatan->nama_jabatan }}</p>

                    <ul class="list-group mb-3">
                        <li class="list-group-item" style="border: none; border-bottom: 1px solid">
                            <b>Email</b> <a class="float-end" style="color: black">{{ $karyawan->email }}</a>
                        </li>
                        <li class="list-group-item" style="border: none; border-bottom: 1px solid">
                            <b>Username</b> <a class="float-end" style="color: black">{{ $karyawan->username }}</a>
                        </li>
                        <li class="list-group-item" style="border: none; border-bottom: 1px solid">
                            <b>Telepon</b> <a class="float-end" style="color: black">{{ $karyawan->telepon }}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <h3 class="text-center">Tanda Tangan</h3>
                    </div>

                    <h3 class="profile-username text-center">{{ $karyawan->name }}</h3>

                    <p class="text-muted text-center">{{ $karyawan->Jabatan->nama_jabatan }}</p>

                    <div class="text-center">
                        @if($karyawan->ttd_karyawan == null)
                        <h2 class="text-center">Belum Upload Tanda Tangan</h2>
                        @else
                        <img class="ttd-user-img img-fluid img-circle"
                            src="{{ url('storage/'.$karyawan->ttd_karyawan) }}" alt="User Signature">
                        @endif
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                        <form method="post" action="{{ url('/pegawai/proses-edit/'.$karyawan->id) }}"
                            enctype="multipart/form-data">
                            @method('put')
                            @csrf
                            <div class="form-row">
                                <div class="col mb-4">
                                    <label for="name">Nama Pegawai</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" autofocus value="{{ old('name', $karyawan->name) }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                    <label for="nik">No. Induk Karyawan</label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                           id="nik" name="nik" autofocus value="{{ old('nik', $karyawan->nik) }}">
                                    @error('nik')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <input type="hidden" name="password" value="{{ $karyawan->password }}">
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                           id="username" name="username"
                                           value="{{ old('username', $karyawan->username) }}">
                                    @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $karyawan->email) }}">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                    <label for="tgl_lahir">Tanggal Lahir</label>
                                    <input type="datetime" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                           id="tgl_lahir" name="tgl_lahir"
                                           value="{{ old('tgl_lahir', $karyawan->tgl_lahir) }}">
                                    @error('tgl_lahir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                    <label for="telepon">Nomor Telfon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                           id="telepon" name="telepon" value="{{ old('telepon', $karyawan->telepon) }}">
                                    @error('telepon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                    <label for="rekening">Rekening</label>
                                    <input type="number" class="form-control @error('rekening') is-invalid @enderror"
                                        id="rekening" name="rekening"
                                        value="{{ old('rekening', $karyawan->rekening) }}">
                                    @error('rekening')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                    <label for="tgl_join">Tanggal Masuk Perusahaan</label>
                                    <input type="datetime" class="form-control @error('tgl_join') is-invalid @enderror"
                                           id="tgl_join" name="tgl_join"
                                           value="{{ old('tgl_join', $karyawan->tgl_join) }}">
                                    @error('tgl_join')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">

                                <div class="col mb-4">
                                    <label for="lokasi_id">Lokasi Kantor</label>
                                    <select name="lokasi_id" id="lokasi_id"
                                        class="form-control @error('lokasi_id') is-invalid @enderror selectpicker"
                                        data-live-search="true">
                                        @foreach ($data_lokasi as $dl)
                                        @if(old('lokasi_id', $karyawan->lokasi_id) == $dl->id)
                                        <option value="{{ $dl->id }}" selected>{{ $dl->nama_lokasi }}</option>
                                        @else
                                        <option value="{{ $dl->id }}">{{ $dl->nama_lokasi }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('lokasi_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                        <?php $gender = array(
                                        [
                                            "gender" => "Laki-Laki"
                                        ],
                                        [
                                            "gender" => "Perempuan"
                                        ]);
                                        ?>
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender"
                                            class="form-control @error('gender') is-invalid @enderror selectpicker"
                                            data-live-search="true">
                                        @foreach ($gender as $g)
                                            @if(old('gender', $karyawan->gender) == $g["gender"])
                                                <option value="{{ $g["gender"] }}" selected>{{ $g["gender"] }}</option>
                                            @else
                                                <option value="{{ $g["gender"] }}">{{ $g["gender"] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                    <?php $sNikah = array(
                                        [
                                            "status" => "Menikah"
                                        ],
                                        [
                                            "status" => "Lajang"
                                        ]);
                                        ?>
                                    <label for="status_nikah">Status Pernikahan</label>
                                    <select name="status_nikah" id="status_nikah"
                                        class="form-control @error('status_nikah') is-invalid @enderror selectpicker"
                                        data-live-search="true">
                                        @foreach ($sNikah as $s)
                                        @if(old('status_nikah', $karyawan->status_nikah) == $s["status"])
                                        <option value="{{ $s["status"] }}" selected>{{ $s["status"] }}</option>
                                        @else
                                        <option value="{{ $s["status"] }}">{{ $s["status"] }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('status_nikah')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                        <?php $is_admin = array(
                                        [
                                            "is_admin" => "admin"
                                        ],
                                        [
                                            "is_admin" => "user"
                                        ],
                                        [
                                            "is_admin" => "head"
                                        ],
                                        [
                                            "is_admin" => "manager"
                                        ],
                                        [
                                            "is_admin" => "director"
                                        ]);
                                        ?>
                                    <label for="is_admin">Level User</label>
                                    <select name="is_admin" id="is_admin"
                                            class="form-control @error('is_admin') is-invalid @enderror selectpicker"
                                            data-live-search="true">
                                        @foreach ($is_admin as $a)
                                            @if(old('is_admin', $karyawan->is_admin) == $a["is_admin"])
                                                <option value="{{ $a["is_admin"] }}" selected>{{ $a["is_admin"] }}</option>
                                            @else
                                                <option value="{{ $a["is_admin"] }}">{{ $a["is_admin"] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('is_admin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                        <?php $tipe_karyawan = array(
                                        [
                                            "tipe_karyawan" => "Kontrak"
                                        ],
                                        [
                                            "tipe_karyawan" => "Tetap"
                                        ]);
                                        ?>
                                    <label for="tipe_karyawan">Perjanjian Karyawan</label>
                                    <select name="tipe_karyawan" id="tipe_karyawan"
                                            class="form-control @error('$tipe_karyawan') is-invalid @enderror selectpicker"
                                            data-live-search="true">
                                        @foreach ($tipe_karyawan as $tk)
                                            @if(old('tipe_karyawan', $karyawan->tipe_karyawan) === strtolower($tk["tipe_karyawan"]))
                                                <option value="{{ strtolower($tk["tipe_karyawan"]) }}" selected>{{ $tk["tipe_karyawan"] }}</option>
                                            @else
                                                <option value="{{ strtolower($tk["tipe_karyawan"]) }}">{{ $tk["tipe_karyawan"] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('tipe_karyawan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col mb-4">
                                    <label for="jabatan_id">Jabatan</label>
                                    <select name="jabatan_id" id="jabatan_id"
                                        class="form-control @error('jabatan_id') is-invalid @enderror selectpicker"
                                        data-live-search="true">
                                        @foreach ($data_jabatan as $dj)
                                        @if(old('jabatan_id', $karyawan->jabatan_id) == $dj->id)
                                        <option value="{{ $dj->id }}" selected>{{ $dj->nama_jabatan }}</option>
                                        @else
                                        <option value="{{ $dj->id }}">{{ $dj->nama_jabatan }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('jabatan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                    <label for="golongan_id">Golongan</label>
                                    <select name="golongan_id" id="golongan_id"
                                            class="form-control @error('golongan_id') is-invalid @enderror selectpicker"
                                            data-live-search="true">
                                        @foreach ($data_golongan as $dg)
                                            @if(old('golongan_id', $karyawan->golongan_id) == $dg->id)
                                                <option value="{{ $dg->id }}" selected>{{ $dg->name}}</option>
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
                                <div class="col mb-4">
                                    <label for="alamat">Alamat</label>
                                    <textarea name="alamat" id="alamat"
                                              class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                    @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-4">
                                    <label for="foto_karyawan" class="form-label">Foto Pegawai</label>
                                    <input class="form-control @error('foto_karyawan') is-invalid @enderror" type="file"
                                        id="foto_karyawan" name="foto_karyawan">
                                    @error('foto_karyawan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <input type="hidden" name="foto_karyawan_lama" value="{{ $karyawan->foto_karyawan ?? '' }}">
                                <div class="col mb-4">
                                    <label for="ttd_karyawan" class="form-label">Tanda Tangan Pegawai</label>
                                    <input class="form-control @error('ttd_karyawan') is-invalid @enderror" type="file"
                                        id="ttd_karyawan" name="ttd_karyawan">
                                    @error('ttd_karyawan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div id="cuti_izin_section">
                                @include('karyawan.partials.cuti_izin_tambah_karyawan', ['cutiIzin' => $data_cuti_izin ?? null])
                            </div>
                            <div id="upah_section">
                                @include('karyawan.partials.upah_tambah_karyawan', ['upah' => $data_upah ?? null])
{{--                                @include('karyawan.partials.dynamic_upah_tambah_karyawan', ['dynamicUpah' => $data_dynamic_upah ?? null])--}}
                            </div>
                            <div id="deduksi_section">
                                @include('karyawan.partials.deduksi_tambah_karyawan',
                                    ['data_deduksi' => $data_deduksi ?? null, 'tipeKaryawan' => $karyawan->tipe_karyawan ?? null,
                                    'data_bpjs_kesehatan' => $data_bpjs_kesehatan ?? null, 'data_bpjs_ketenagakerjaan' => $data_bpjs_ketenagakerjaan ?? null, 'data_bpjs_ketenagakerjaan_jkk' => $data_bpjs_ketenagakerjaan_jkk ?? null])
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('.money').mask('000,000,000,000,000', {
        reverse: true
    });

    function sendAjaxRequest() {
        var golonganId = $('#golongan_id').val();
        var tipeKaryawan = $('#tipe_karyawan').val();

        if (golonganId !== '' && tipeKaryawan !== '') {
            $.ajax({
                url: '{{ url("/pegawai/detail/".$karyawan->id) }}',
                type: 'GET',
                data: {
                    golongan_id: golonganId,
                    tipe_karyawan: tipeKaryawan
                },
                success: function(response) {
                    $('#cuti_izin_section').html(response.cuti_izin_view);
                    $('#upah_section').html(response.upah_view);
                    // $('#upah_section').html(response.dynamic_upah_view);
                    $('#deduksi_section').html(response.deduksi_view);
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        }
    }

    $('#golongan_id, #tipe_karyawan').on('change', sendAjaxRequest);
});
</script>
@endpush
@endsection
