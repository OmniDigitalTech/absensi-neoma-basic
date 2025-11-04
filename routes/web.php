<?php

use App\Http\Controllers\DinasLuar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\authController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DataCutiController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\OncallController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\jabatanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\karyawanController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AutoShiftController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\UpahController;
use App\Http\Controllers\DynamicUpahController;
use App\Http\Controllers\DeduksiController;
use App\Http\Controllers\BpjsController;
use App\Http\Controllers\StatusPtkpController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [authController::class, 'index'])->name('login')->middleware('guest');
Route::get('/login', [authController::class, 'login'])->name('login')->middleware('guest');
Route::get('/get-started', [authController::class, 'getStarted'])->name('getStarted')->middleware('guest');
Route::get('/welcome', [authController::class, 'welcome'])->name('welcome')->middleware('guest');
Route::get('/presensi', [authController::class, 'presensi']);
Route::get('/presensi-pulang', [authController::class, 'presensiPulang']);
Route::post('/presensi/store', [authController::class, 'presensiStore']);
Route::post('/presensi-pulang/store', [authController::class, 'presensiPulangStore']);
Route::get('/ajaxGetNeural', [authController::class, 'ajaxGetNeural']);
Route::get('/register', [authController::class, 'register'])->middleware('guest');
Route::post('/register-proses', [authController::class, 'registerProses'])->middleware('guest');
Route::post('/login-proses', [authController::class, 'loginProses'])->middleware('guest');
Route::post('/login-proses-user', [authController::class, 'loginProsesUser'])->middleware('guest');
Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::post('/logout', [authController::class, 'logout'])->middleware('auth');
Route::get('/pegawai', [karyawanController::class, 'index'])->middleware('auth');
Route::get('/euforia', [karyawanController::class, 'euforia'])->middleware('auth');
Route::get('/pegawai/tambah-pegawai', [karyawanController::class, 'tambahKaryawan'])->middleware('admin');
Route::post('/pegawai/tambah-pegawai-proses', [karyawanController::class, 'tambahKaryawanProses'])->middleware('admin');
Route::post('/pegawai/face/ajaxPhoto', [karyawanController::class, 'ajaxPhoto'])->middleware('admin');
Route::post('/pegawai/face/ajaxDescrip', [karyawanController::class, 'ajaxDescrip'])->middleware('admin');
Route::post('/pegawai/import', [karyawanController::class, 'importUsers'])->middleware('admin');
Route::get('/pegawai/detail/{id}', [karyawanController::class, 'detail'])->middleware('admin');
Route::get('/pegawai/show/{id}', [karyawanController::class, 'show'])->middleware('auth');
Route::get('/pegawai/face/{id}', [karyawanController::class, 'face'])->middleware('admin');
Route::put('/pegawai/proses-edit/{id}', [karyawanController::class, 'editKaryawanProses'])->middleware('admin');
Route::delete('/pegawai/delete/{id}', [karyawanController::class, 'deleteKaryawan'])->middleware('admin');
Route::get('/pegawai/edit-password/{id}', [karyawanController::class, 'editPassword'])->middleware('admin');
Route::put('/pegawai/edit-password-proses/{id}', [karyawanController::class, 'editPasswordProses'])->middleware('admin');
Route::resource('/shift', ShiftController::class)->middleware('admin');
Route::post('/store-form-data', [dashboardController::class, 'storeFormData'])->name('storeFormData');
Route::post('/clear-form-session', [dashboardController::class, 'clearFormSession'])->name('clearSession');

Route::get('/pegawai/shift/{id}', [karyawanController::class, 'shift'])->middleware('admin');
Route::get('/pegawai/dinas-luar/{id}', [karyawanController::class, 'dinasLuar'])->middleware('admin');

Route::post('/pegawai/shift/proses-tambah-shift', [karyawanController::class, 'prosesTambahShift'])->middleware('admin');
Route::post('/pegawai/dinas-luar/proses-tambah-shift', [karyawanController::class, 'prosesTambahDinas'])->middleware('admin');

Route::delete('/pegawai/delete-shift/{id}', [karyawanController::class, 'deleteShift'])->middleware('admin');
Route::delete('/pegawai/delete-dinas/{id}', [karyawanController::class, 'deleteDinas'])->middleware('admin');

Route::get('/pegawai/edit-shift/{id}', [karyawanController::class, 'editShift'])->middleware('admin');
Route::get('/pegawai/edit-dinas/{id}', [karyawanController::class, 'editDinas'])->middleware('admin');

Route::put('/pegawai/proses-edit-shift/{id}', [karyawanController::class, 'prosesEditShift'])->middleware('auth');
Route::put('/pegawai/proses-edit-dinas/{id}', [karyawanController::class, 'prosesEditDinas'])->middleware('auth');

Route::get('/absen', [AbsenController::class, 'index'])->middleware('auth');
Route::get('/dinas-luar', [DinasLuar::class, 'index'])->middleware('auth');

Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth');
Route::get('/notifications/read', [NotificationController::class, 'read'])->middleware('auth');
Route::get('/notifications/unread', [NotificationController::class, 'unread'])->middleware('auth');
Route::get('/notifications/read-message/{id}', [NotificationController::class, 'readMessage'])->middleware('auth');

Route::get('/menu', [dashboardController::class, 'menu'])->middleware('auth');

Route::get('/my-location', [AbsenController::class, 'myLocation'])->middleware('auth');

Route::put('/absen/masuk/{id}', [AbsenController::class, 'absenMasuk'])->middleware('auth');
Route::put('/dinas-luar/masuk/{id}', [DinasLuar::class, 'absenMasukDinas'])->middleware('auth');

Route::put('/absen/pulang/{id}', [AbsenController::class, 'absenPulang'])->middleware('auth');
Route::put('/dinas-luar/pulang/{id}', [DinasLuar::class, 'absenPulangDinas'])->middleware('auth');

Route::get('/data-absen', [AbsenController::class, 'dataAbsen'])->middleware('admin');
Route::get('/data-dinas-luar', [DinasLuar::class, 'dataAbsenDinas'])->middleware('admin');

Route::get('/data-absen/{id}/edit-masuk', [AbsenController::class, 'editMasuk'])->middleware('admin');
Route::get('/maps/{lat}/{long}/{userid}', [AbsenController::class, 'maps'])->middleware('auth');
Route::put('/data-absen/{id}/proses-edit-masuk', [AbsenController::class, 'prosesEditMasuk'])->middleware('admin');
Route::get('/data-absen/{id}/edit-pulang', [AbsenController::class, 'editPulang'])->middleware('admin');
Route::put('/data-absen/{id}/proses-edit-pulang', [AbsenController::class, 'prosesEditPulang'])->middleware('admin');
Route::delete('/data-absen/{id}/delete', [AbsenController::class, 'deleteAdmin'])->middleware('admin');

Route::get('/my-absen', [AbsenController::class, 'myAbsen'])->middleware('auth');
Route::get('/my-absen/pengajuan/{id}', [AbsenController::class, 'pengajuan'])->middleware('auth');
Route::post('/my-absen/pengajuan-proses/{id}', [AbsenController::class, 'pengajuanProses'])->middleware('auth');
Route::get('/my-dinas-luar', [DinasLuar::class, 'myDinasLuar'])->middleware('auth');
Route::get('/pengajuan-absensi', [AbsenController::class, 'pengajuanAbsensi'])->middleware('auth');
Route::get('/pengajuan-absensi/edit/{id}', [AbsenController::class, 'editPengajuanAbsensi'])->middleware('auth');
Route::post('/pengajuan-absensi/update/{id}', [AbsenController::class, 'updatePengajuanAbsensi'])->middleware('auth');

Route::get('/lembur', [LemburController::class, 'index'])->middleware('auth');
Route::post('/lembur/masuk', [LemburController::class, 'masuk'])->middleware('auth');
Route::put('/lembur/pulang/{id}', [LemburController::class, 'pulang'])->middleware('auth');
Route::get('/data-lembur', [LemburController::class, 'dataLembur'])->middleware('admin');
Route::post('/data-lembur/approval/{id}', [LemburController::class, 'approval'])->middleware('check_lvl:admin,head');
Route::get('/my-lembur', [LemburController::class, 'myLembur'])->middleware('auth');

Route::get('/oncall', [OncallController::class, 'index'])->middleware('auth');
Route::post('/oncall/masuk', [OncallController::class, 'masuk'])->middleware('auth');
Route::put('/oncall/pulang/{id}', [OncallController::class, 'pulang'])->middleware('auth');
Route::get('/data-oncall', [OncallController::class, 'dataOncall'])->middleware('admin');
Route::post('/data-oncall/approval/{id}', [OncallController::class, 'approval'])->middleware('check_lvl:admin,head');
Route::get('/my-oncall', [OncallController::class, 'myOncall'])->middleware('auth');

Route::get('/rekap-data', [RekapDataController::class, 'index'])->middleware('admin');
Route::get('/rekap-data/export', [RekapDataController::class, 'export'])->middleware('admin');
Route::get('/rekap-data/get-data', [RekapDataController::class, 'getData'])->middleware('admin');
Route::get('/rekap-data/detail-pdf', [RekapDataController::class, 'detailPdf'])->middleware('admin');
Route::get('/rekap-data/rekap-pdf', [RekapDataController::class, 'rekapPdf'])->middleware('admin');
Route::get('/rekap-data/payroll/{id}', [RekapDataController::class, 'payroll'])->middleware('admin');
Route::post('/rekap-data/payroll/tambah', [RekapDataController::class, 'tambahPayroll'])->middleware('admin');

//Route::get('/cuti/{id}/download', [CutiController::class, 'downloadSurat'])->middleware('auth');
Route::get('/cuti', [CutiController::class, 'index'])->middleware('auth');
Route::post('/cuti/tambah', [CutiController::class, 'tambah'])->middleware('auth');
Route::delete('/cuti/delete/{id}', [CutiController::class, 'delete'])->middleware('auth');
Route::get('/cuti/edit/{id}', [CutiController::class, 'edit'])->middleware('auth');
Route::put('/cuti/proses-edit/{id}', [CutiController::class, 'editProses'])->middleware('auth');
Route::post('/cuti/action/{id}', [CutiController::class, 'actionApproval'])->middleware('auth');
Route::get('/data-cuti', [CutiController::class, 'dataCuti'])->middleware('admin');
Route::post('/data-cuti/getuserid', [CutiController::class, 'getUserId'])->middleware('admin');
Route::get('/data-cuti/cuti-izin', [DataCutiController::class, 'index'])->middleware('admin');
Route::get('/data-cuti/tambah-cuti-izin', [DataCutiController::class, 'tambahCutiIzin'])->middleware('auth');
Route::post('/data-cuti/proses-tambah-cuti-izin', [DataCutiController::class, 'tambahDataCutiIzinProses'])->middleware('admin');
Route::get('/data-cuti/edit-cuti-izin/{id}', [DataCutiController::class, 'editCutiIzin'])->middleware('admin');
Route::put('/data-cuti/proses-edit-cuti-izin/{id}', [DataCutiController::class, 'editCutiIzinProses'])->middleware('admin');
Route::delete('/data-cuti/delete-cuti-izin/{id}', [DataCutiController::class, 'deleteCutiIzin'])->middleware('admin');
Route::delete('/data-cuti/delete/{id}', [CutiController::class, 'deleteAdmin'])->middleware('admin');
Route::get('/data-cuti/edit/{id}', [CutiController::class, 'editAdmin'])->middleware('admin');
Route::put('/data-cuti/edit-proses/{id}', [CutiController::class, 'editAdminProses'])->middleware('admin');
Route::get('/preview-surat-cuti/{id}', [CutiController::class, 'previewSurat'])->middleware('admin');
Route::get('/data-cuti/download/{id}', [CutiController::class, 'downloadSurat'])->middleware('auth');
Route::get('/my-profile', [karyawanController::class, 'myProfile'])->middleware('auth');
Route::put('/my-profile/update/{id}', [karyawanController::class, 'myProfileUpdate'])->middleware('auth');
Route::get('/my-profile/edit-password', [karyawanController::class, 'editPassMyProfile'])->middleware('auth');
Route::put('/my-profile/edit-password-proses/{id}', [karyawanController::class, 'editPassMyProfileProses'])->middleware('auth');

Route::get('/lokasi-kantor', [LokasiController::class, 'index'])->middleware('admin');
Route::get('/lokasi-kantor/tambah', [LokasiController::class, 'tambahLokasi'])->middleware('admin');
Route::post('/lokasi-kantor/tambah-proses', [LokasiController::class, 'prosesTambahLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/edit/{id}', [LokasiController::class, 'editLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/update/{id}', [LokasiController::class, 'updateLokasi'])->middleware('admin');
Route::put('/lokasi-kantor/radius/{id}', [LokasiController::class, 'updateRadiusLokasi'])->middleware('admin');
Route::delete('/lokasi-kantor/delete/{id}', [LokasiController::class, 'deleteLokasi'])->middleware('admin');
Route::get('/lokasi-kantor/pending-location', [LokasiController::class, 'pendingLocation'])->middleware('admin');
Route::put('/lokasi-kantor/update-pending-location/{id}', [LokasiController::class, 'UpdatePendingLocation'])->middleware('admin');

Route::get('/request-location', [LokasiController::class, 'requestLocation'])->middleware('auth');
Route::get('/request-location/tambah', [LokasiController::class, 'tambahRequestLocation'])->middleware('auth');
Route::post('/request-location/tambah-proses', [LokasiController::class, 'prosesTambahRequestLocation'])->middleware('auth');
Route::get('/request-location/edit/{id}', [LokasiController::class, 'editRequestLocation'])->middleware('auth');
Route::put('/request-location/update/{id}', [LokasiController::class, 'updateRequestLocation'])->middleware('auth');
Route::put('/request-location/radius/{id}', [LokasiController::class, 'updateRadiusRequestLocation'])->middleware('auth');
Route::delete('/request-location/delete/{id}', [LokasiController::class, 'deleteRequestLocation'])->middleware('auth');

Route::get('/reset-cuti', [karyawanController::class, 'resetCuti'])->middleware('admin');
Route::put('/reset-cuti/{id}', [karyawanController::class, 'resetCutiProses'])->middleware('admin');

Route::get('/jabatan', [jabatanController::class, 'index'])->middleware('admin');
Route::get('/jabatan/create', [jabatanController::class, 'create'])->middleware('admin');
Route::post('/jabatan/insert', [jabatanController::class, 'insert'])->middleware('admin');
Route::get('/jabatan/edit/{id}', [jabatanController::class, 'edit'])->middleware('admin');
Route::put('/jabatan/update/{id}', [jabatanController::class, 'update'])->middleware('admin');
Route::delete('/jabatan/delete/{id}', [jabatanController::class, 'delete'])->middleware('admin');

Route::get('/golongan', [GolonganController::class, 'index'])->middleware('admin');
Route::get('/golongan/tambah', [GolonganController::class, 'tambah'])->middleware('admin');
Route::post('/golongan/tambah-proses', [GolonganController::class, 'tambahProses'])->middleware('admin');
Route::get('/golongan/edit/{id}', [GolonganController::class, 'edit'])->middleware('admin');
Route::put('/golongan/update/{id}', [GolonganController::class, 'update'])->middleware('admin');
Route::delete('/golongan/delete/{id}', [GolonganController::class, 'delete'])->middleware('admin');

Route::get('/dokumen', [DokumenController::class, 'index'])->middleware('admin');
Route::get('/dokumen/tambah', [DokumenController::class, 'tambah'])->middleware('admin');
Route::post('/dokumen/tambah-proses', [DokumenController::class, 'tambahProses'])->middleware('admin');
Route::get('/dokumen/edit/{id}', [DokumenController::class, 'edit'])->middleware('admin');
Route::put('/dokumen/edit-proses/{id}', [DokumenController::class, 'editProses'])->middleware('admin');
Route::delete('/dokumen/delete/{id}', [DokumenController::class, 'delete'])->middleware('admin');
Route::get('/my-dokumen', [DokumenController::class, 'myDokumen'])->middleware('auth');
Route::get('/my-dokumen/tambah', [DokumenController::class, 'myDokumenTambah'])->middleware('auth');
Route::post('/my-dokumen/tambah-proses', [DokumenController::class, 'myDokumenTambahProses'])->middleware('auth');
Route::get('/my-dokumen/edit/{id}', [DokumenController::class, 'myDokumenEdit'])->middleware('auth');
Route::put('/my-dokumen/edit-proses/{id}', [DokumenController::class, 'myDokumenEditProses'])->middleware('auth');
Route::delete('/my-dokumen/delete/{id}', [DokumenController::class, 'myDokumenDelete'])->middleware('auth');

Route::get('/auto-shift', [AutoShiftController::class, 'index'])->middleware('admin');
Route::get('/auto-shift/tambah', [AutoShiftController::class, 'tambah'])->middleware('admin');
Route::post('/auto-shift/store', [AutoShiftController::class, 'store'])->middleware('admin');
Route::get('/auto-shift/{id}/edit', [AutoShiftController::class, 'edit'])->middleware('admin');
Route::put('/auto-shift/update/{id}', [AutoShiftController::class, 'update'])->middleware('admin');
Route::delete('/auto-shift/delete/{id}', [AutoShiftController::class, 'delete'])->middleware('admin');

Route::get('/file', [FileController::class, 'index'])->middleware('admin');
Route::get('/file/upload', [FileController::class, 'upload'])->middleware('admin');
Route::post('/file/upload-proses', [FileController::class, 'uploadProses'])->middleware('admin');
Route::get('/file/edit/{id}', [FileController::class, 'edit'])->middleware('admin');
Route::put('/file/update/{id}', [FileController::class, 'update'])->middleware('admin');
Route::delete('/file/delete/{id}', [FileController::class, 'delete'])->middleware('admin');

Route::get('/my-file', [FileController::class, 'myFile'])->middleware('auth');
Route::get('/my-file/upload', [FileController::class, 'myFileUpload'])->middleware('auth');
Route::post('/my-file/upload-proses', [FileController::class, 'myFileUploadProses'])->middleware('auth');
Route::get('/my-file/edit/{id}', [FileController::class, 'myFileEdit'])->middleware('auth');
Route::put('/my-file/update/{id}', [FileController::class, 'myFileUpdate'])->middleware('auth');
Route::delete('/my-file/delete/{id}', [FileController::class, 'myFileDelete'])->middleware('auth');

Route::get('/tunjangan', [TunjanganController::class, 'index'])->middleware('admin');
Route::get('/tunjangan/tambah', [TunjanganController::class, 'tambah'])->middleware('admin');
Route::post('/tunjangan/tambah-proses', [TunjanganController::class, 'tambahProses'])->middleware('admin');
Route::get('/tunjangan/{id}/edit', [TunjanganController::class, 'edit'])->middleware('admin');
Route::put('/tunjangan/{id}/update', [TunjanganController::class, 'update'])->middleware('admin');
Route::delete('/tunjangan/{id}/delete', [TunjanganController::class, 'delete'])->middleware('admin');

Route::get('/upah', [UpahController::class, 'index'])->middleware('admin');
Route::get('/upah/tambah', [UpahController::class, 'tambah'])->middleware('admin');
Route::post('/upah/tambah-proses', [UpahController::class, 'tambahProses'])->middleware('admin');
Route::get('/upah/edit/{id}', [UpahController::class, 'edit'])->middleware('admin');
Route::put('/upah/update/{id}', [UpahController::class, 'update'])->middleware('admin');
Route::delete('/upah/delete/{id}', [UpahController::class, 'delete'])->middleware('admin');

Route::get('/dynamic-upah', [DynamicUpahController::class, 'index'])->middleware('admin');
Route::get('/dynamic-upah/tambah', [DynamicUpahController::class, 'tambah'])->middleware('admin');
Route::post('/dynamic-upah/tambah-proses', [DynamicUpahController::class, 'tambahProses'])->middleware('admin');
Route::get('/dynamic-upah/edit/{id}', [DynamicUpahController::class, 'edit'])->middleware('admin');
Route::put('/dynamic-upah/update/{id}', [DynamicUpahController::class, 'update'])->middleware('admin');
Route::delete('/dynamic-upah/delete/{id}', [DynamicUpahController::class, 'delete'])->middleware('admin');

Route::get('/deduksi', [DeduksiController::class, 'index'])->middleware('admin');
Route::get('/deduksi/tambah-deduksi', [DeduksiController::class, 'tambahDeduksi'])->middleware('admin');
Route::post('/deduksi/proses-tambah-deduksi', [DeduksiController::class, 'prosesTambahDeduksi'])->middleware('admin');
Route::get('/deduksi/edit-deduksi/{id}', [DeduksiController::class, 'editDeduksi'])->middleware('admin');
Route::put('/deduksi/proses-edit-deduksi/{id}', [DeduksiController::class, 'prosesEditDeduksi'])->middleware('admin');
Route::delete('/deduksi/delete-deduksi/{id}', [DeduksiController::class, 'deleteDeduksi'])->middleware('admin');

Route::get('/bpjs', [BpjsController::class, 'index'])->middleware('admin');
Route::get('/bpjs/tambah-bpjs', [BpjsController::class, 'tambahKetenagakerjaan'])->middleware('admin');
Route::post('/bpjs/proses-tambah-bpjs', [BpjsController::class, 'prosesTambahKetenagakerjaan'])->middleware('admin');
Route::get('/bpjs/edit-bpjs/{id}', [BpjsController::class, 'editKetenagakerjaan'])->middleware('admin');
Route::put('/bpjs/proses-edit-bpjs/{id}', [BpjsController::class, 'prosesEditKetenagakerjaan'])->middleware('admin');
Route::delete('/bpjs/delete-bpjs/{id}', [BpjsController::class, 'deleteKetenagakerjaan'])->middleware('admin');


Route::get('/payroll', [PayrollController::class, 'index'])->middleware('auth');
Route::get('/payroll/tambah', [PayrollController::class, 'tambah'])->middleware('admin');
Route::post('/payroll/tambah-proses', [PayrollController::class, 'tambahProses'])->middleware('admin');
Route::get('/payroll/{id}/edit', [PayrollController::class, 'edit'])->middleware('admin');
Route::get('/payroll/{id}/download', [PayrollController::class, 'download'])->middleware('auth');
Route::put('/payroll/{id}/update', [PayrollController::class, 'update'])->middleware('admin');
Route::delete('/payroll/{id}/delete', [PayrollController::class, 'delete'])->middleware('admin');

Route::get('/kasbon', [KasbonController::class, 'index'])->middleware('auth');
Route::get('/kasbon/tambah', [KasbonController::class, 'tambah'])->middleware('auth');
Route::post('/kasbon/tambah-proses', [KasbonController::class, 'tambahProses'])->middleware('auth');
Route::get('/kasbon/edit/{id}', [KasbonController::class, 'edit'])->middleware('auth');
Route::put('/kasbon/update/{id}', [KasbonController::class, 'update'])->middleware('auth');
Route::delete('/kasbon/delete/{id}', [KasbonController::class, 'delete'])->middleware('auth');

Route::get('/status-ptkp', [StatusPtkpController::class, 'index'])->middleware('admin');
Route::get('/status-ptkp/tambah', [StatusPtkpController::class, 'tambah'])->middleware('admin');
Route::post('/status-ptkp/tambah-proses', [StatusPtkpController::class, 'tambahProses'])->middleware('admin');
Route::get('/status-ptkp/{id}/edit', [StatusPtkpController::class, 'edit'])->middleware('admin');
Route::put('/status-ptkp/{id}/update', [StatusPtkpController::class, 'update'])->middleware('admin');
Route::delete('/status-ptkp/{id}/delete', [StatusPtkpController::class, 'delete'])->middleware('admin');

Route::get('/pajak-pph21', [PajakController::class, 'index'])->middleware('admin');
Route::get('/pajak-pph21/tambah', [PajakController::class, 'tambah'])->middleware('admin');
Route::post('/pajak-pph21/tambah-proses', [PajakController::class, 'tambahProses'])->middleware('admin');
Route::get('/pajak-pph21/{id}/edit', [PajakController::class, 'edit'])->middleware('admin');
Route::put('/pajak-pph21/{id}/update', [PajakController::class, 'update'])->middleware('admin');
Route::delete('/pajak-pph21/{id}/delete', [PajakController::class, 'delete'])->middleware('admin');

Route::get('/data-absen/export', [AbsenController::class, 'exportDataAbsen'])->middleware('admin');

Route::get('/settings', [SettingsController::class, 'index'])->middleware('admin');
Route::post('/settings/store', [SettingsController::class, 'store'])->middleware('admin');

Route::get('/scheduler-trigger/rE3a1Hjq4hMpUlblhqrBc8hQOEQ719iKQJ5AMi9HFpbQCDWHU407cqB2SXX6oSOf', static function () {
    Artisan::call('schedule:run');

    return 'Scheduler executed!';
});

Route::get('/reset', static function () {
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('migrate:fresh --seed');
    Artisan::call('storage:link');
});
