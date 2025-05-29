<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DataPemilihController;
use App\Http\Controllers\ClusteringController;
use App\Http\Controllers\HasilEvaluasiController;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Row;
use App\Http\Controllers\AkunController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

Route::get('/home', function () {
    return view('home');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('forgot');
Route::post('/forgot-password', [AuthController::class, 'forgotAction'])->name('forgot.action');
Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('reset.form');
Route::post('/reset-password', [AuthController::class, 'resetAction'])->name('reset.action');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerAction'])->name('register.action');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/data-pemilih', [DataPemilihController::class, 'index'])->name('data-pemilih.index');
Route::get('/data-pemilih/create', [DataPemilihController::class, 'create'])->name('data-pemilih.create');
Route::post('/data-pemilih', [DataPemilihController::class, 'store'])->name('data-pemilih.store');
Route::get('/data-pemilih/{id}/edit', [DataPemilihController::class, 'edit'])->name('data-pemilih.edit');
Route::put('/data-pemilih/{id}', [DataPemilihController::class, 'update'])->name('data-pemilih.update');
Route::delete('/data-pemilih/{id}', [DataPemilihController::class, 'destroy'])->name('data-pemilih.destroy');
Route::get('/data-pemilih/import', [DataPemilihController::class, 'importForm'])->name('data-pemilih.import.form');
Route::post('/data-pemilih/import', [DataPemilihController::class, 'import'])->name('data-pemilih.import');

Route::get('/clustering', [ClusteringController::class, 'index'])->name('clustering.index');
Route::post('/clustering/proses', [ClusteringController::class, 'process'])->name('clustering.process');

Route::get('/hasil-evaluasi', [HasilEvaluasiController::class, 'index'])->name('hasil.evaluasi');
Route::get('/hasil-evaluasi/preview', [HasilEvaluasiController::class, 'preview'])->name('hasil-evaluasi.preview');
Route::get('/hasil-evaluasi/export', [HasilEvaluasiController::class, 'exportPDF'])->name('hasil-evaluasi.export');
Route::get('/hasil-evaluasi/export-wilayah', [HasilEvaluasiController::class, 'exportWilayahForm'])->name('hasil-evaluasi.export-wilayah.form');
Route::post('/hasil-evaluasi/export-wilayah', [HasilEvaluasiController::class, 'exportWilayah'])->name('hasil-evaluasi.export-wilayah');
Route::get('/hasil-evaluasi/export-semua', [HasilEvaluasiController::class, 'exportsemua'])->name('hasil-evaluasi.export-semua');

Route::get('/akun/pengaturan', [AkunController::class, 'pengaturan'])->name('akun.pengaturan')->middleware('auth');
Route::post('/akun/pengaturan', [AkunController::class, 'update'])->name('akun.update')->middleware('auth');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/cek-login', function() {
    if (Auth::check()) {
        return 'Sudah login sebagai: ' . Auth::user()->name;
    } else {
        return redirect('/login');
    }
});


