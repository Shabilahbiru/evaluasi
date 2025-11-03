<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    LoginController,
    DashboardController,
    ProfilBakesbangpolController,
    DataPemilihController,
    ClusteringController,
    HasilEvaluasiController,
    AkunController,
    UserRoleController,
    DataPemilihScanController
};

Route::get('/', function () {
    return view('home');
});

Route::get('/home', fn () => view('home'))->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerAction'])->name('register.action');

Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('forgot');
Route::post('/forgot-password', [AuthController::class, 'forgotAction'])->name('forgot.action');
Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('reset.form');
Route::post('/reset-password', [AuthController::class, 'resetAction'])->name('reset.action');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/set-jenis-pemilu', [DashboardController::class, 'setJenisPemilu'])->name('set.jenis.pemilu');

    Route::get('/profil-bakesbangpol/{slug}', [ProfilBakesbangpolController::class, 'show'])->name('profil.bakesbangpol.section');

    Route::middleware(['role:admin_master'])->group(function () {
        Route::get('/ubah-role', [AkunController::class, 'ubahrole'])->name('users.ubah-role');
        Route::get('/kelola-role', [UserRoleController::class, 'index'])->name('users.ubah-role');
        Route::post('/kelola-role/update', [UserRoleController::class, 'update'])->name('users.update-role');
        
    });
   


    Route::middleware(['role:reviewer'])->group(function () {
        Route::get('/data-pemilih', [DataPemilihController::class, 'index'])->name('data-pemilih.index');
    });

    // Data Pemilih
        Route::resource('/data-pemilih', DataPemilihController::class)->except(['show']);
        Route::get('/data-pemilih/import', [DataPemilihController::class, 'importForm'])->name('data-pemilih.import.form');
        Route::post('/data-pemilih/import', [DataPemilihController::class, 'import'])->name('data-pemilih.import');

        // Scan Upload
        Route::post('/data-pemilih/scan/upload-api', [DataPemilihScanController::class, 'uploadWithApi'])->name('data-pemilih.scan.upload.api');
        Route::post('/data-pemilih/scan/confirm', [DataPemilihScanController::class, 'confirmSave'])->name('data-pemilih.scan.confirm');
        Route::post('/data-pemilih/upload-dpt', [DataPemilihScanController::class, 'uploadDPT'])->name('upload.dpt');
        Route::post('/data-pemilih/upload-suara', [DataPemilihScanController::class, 'uploadSuara'])->name('upload.suara');

        // Clustering
        Route::get('/clustering', [ClusteringController::class, 'index'])->name('clustering.index');
        Route::post('/clustering/proses', [ClusteringController::class, 'process'])->name('clustering.process');
        Route::get('/clustering/export', [ClusteringController::class, 'export'])->name('clustering.export');

 
    Route::get('/hasil-evaluasi', [HasilEvaluasiController::class, 'index'])->name('hasil.evaluasi');
    Route::get('/hasil-evaluasi/preview', [HasilEvaluasiController::class, 'preview'])->name('hasil-evaluasi.preview');
    Route::get('/hasil-evaluasi/export', [HasilEvaluasiController::class, 'exportPDF'])->name('hasil-evaluasi.export');
    Route::get('/hasil-evaluasi/export-wilayah', [HasilEvaluasiController::class, 'exportWilayahForm'])->name('hasil-evaluasi.export-wilayah.form');
    Route::post('/hasil-evaluasi/export-wilayah', [HasilEvaluasiController::class, 'exportWilayah'])->name('hasil-evaluasi.export-wilayah');
    Route::get('/hasil-evaluasi/export-semua', [HasilEvaluasiController::class, 'exportsemua'])->name('hasil-evaluasi.export-semua');


    Route::get('/akun/pengaturan', [AkunController::class, 'pengaturan'])->name('akun.pengaturan');
    Route::post('/akun/pengaturan', [AkunController::class, 'update'])->name('akun.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/cek-login', fn () => 'Login sebagai: ' . Auth::user()->name);
});

Route::get('/profil-bakesbangpol', function () {
    return view('profil-bakesbangpol');
})->name('profil-bakesbangpol');

