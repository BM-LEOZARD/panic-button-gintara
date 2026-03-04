<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\TugasController;
use App\Http\Controllers\EndUserDashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\SuperAdmin\DataAdminController;
use App\Http\Controllers\SuperAdmin\DataPelangganController;
use App\Http\Controllers\SuperAdmin\DataPendaftarController;
use App\Http\Controllers\SuperAdmin\DataWilayahController;
use App\Http\Controllers\SuperAdmin\KinerjaAdminController;
use App\Http\Controllers\SuperAdmin\LaporanPelangganController;
use App\Http\Controllers\SuperAdmin\MonitoringController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\TugasAdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'Admin') return redirect()->route('admin.dashboard');
        if ($role === 'SuperAdmin') return redirect()->route('superadmin.dashboard');
        if ($role === 'EndUser') return redirect()->route('dashboard');
    }
    return view('index');
});
Route::get('/tentang-kami', function () {
    return view('tentang-kami');
});
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/sukses', function () {
    return view('pendaftaran.sukses');
})->name('pendaftaran.sukses');

//! Route EndUser/pelanggan
Route::middleware(['auth', 'enduser'])->group(function () {
    Route::get('/dashboard', [EndUserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/panic', [EndUserDashboardController::class, 'trigger'])->name('dashboard.panic');
    Route::post('/dashboard/profile', [EndUserDashboardController::class, 'updateProfile'])->name('dashboard.update-profile');
});

//! Route Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas/{id}/ambil', [TugasController::class, 'ambil'])->name('tugas.ambil');
    Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{id}/selesai', [TugasController::class, 'selesai'])->name('tugas.selesai');
    Route::post('/tugas/{id}/upload-foto', [TugasController::class, 'uploadFoto'])->name('tugas.upload-foto');
    Route::delete('/tugas/foto/{fotoId}', [TugasController::class, 'hapusFoto'])->name('tugas.hapus-foto');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
});

//! Route SuperAdmin
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/trend', [SuperAdminDashboardController::class, 'trendData'])->name('dashboard.trend');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/poll', [MonitoringController::class, 'poll'])->name('monitoring.poll');
    Route::resource('/data-wilayah', DataWilayahController::class);
    Route::resource('/data-admin', DataAdminController::class);
    Route::post('/data-admin/{id}/restore', [DataAdminController::class, 'restore'])->name('data-admin.restore');
    Route::delete('/data-admin/{id}/force-delete', [DataAdminController::class, 'forceDelete'])->name('data-admin.force-delete');
    Route::resource('/tugas-admin', TugasAdminController::class);
    Route::get('/data-pendaftar', [DataPendaftarController::class, 'index'])->name('data-pendaftar.index');
    Route::get('/data-pendaftar/{id}', [DataPendaftarController::class, 'show'])->name('data-pendaftar.show');
    Route::post('/data-pendaftar/{id}/setujui', [DataPendaftarController::class, 'setujui'])->name('data-pendaftar.setujui');
    Route::post('/data-pendaftar/{id}/tolak', [DataPendaftarController::class, 'tolak'])->name('data-pendaftar.tolak');
    Route::resource('/data-pelanggan', DataPelangganController::class)->only(['index', 'show', 'destroy']);
    Route::post('/data-pelanggan/{id}/restore', [DataPelangganController::class, 'restore'])->name('data-pelanggan.restore');
    Route::delete('/data-pelanggan/{id}/force-delete', [DataPelangganController::class, 'forceDelete'])->name('data-pelanggan.force-delete');
    Route::get('/laporan-pelanggan', [LaporanPelangganController::class, 'index'])->name('laporan-pelanggan.index');
    Route::get('/laporan-pelanggan/{id}', [LaporanPelangganController::class, 'show'])->name('laporan-pelanggan.show');
    Route::get('/kinerja-admin', [KinerjaAdminController::class, 'index'])->name('kinerja-admin.index');
    Route::get('/kinerja-admin/chart', [KinerjaAdminController::class, 'chartData'])->name('kinerja-admin.chart');
});

require __DIR__ . '/auth.php';
