<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('admin')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Pegawai (CRUD + Upload Excel)
    Route::resource('pegawai', PegawaiController::class)->except(['show']);
    Route::post('pegawai/upload', [PegawaiController::class, 'uploadExcel'])->name('pegawai.upload');

    // Route daftar kegiatan
    Route::get('kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');

    // Route tambah kegiatan
    Route::get('kegiatan/create', [AdminController::class, 'createKegiatan'])->name('kegiatan.create');
    Route::post('kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');

    // Route edit kegiatan
    Route::get('kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');

    // Route update kegiatan
    Route::put('kegiatan/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');

    // Route hapus kegiatan
    Route::delete('kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    
    // Route selesai kegiatan
    Route::post('kegiatan/{id}/selesai', [KegiatanController::class, 'selesai'])->name('kegiatan.selesai');

    // Route Laporan kegiatan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');

    // Route download laporan
    Route::get('laporan/{kegiatan}/download-pdf', [LaporanController::class, 'downloadPdfDetail'])->name('laporan.downloadPdfDetail');
    Route::get('laporan/{kegiatan}/download-excel', [LaporanController::class, 'downloadExcelDetail'])->name('laporan.downloadExcelDetail');

    
});
// Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/check', [AbsensiController::class, 'checkAbsensi'])->name('absensi.check');
    Route::post('absensi/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');

// Log Realtime
    Route::get('/absensi/log/{kegiatan_id}', [AbsensiController::class, 'getLogHariIni'])->name('absensi.log');

// Download Qrcodes
    Route::get('/pegawai/download-qrcodes', [PegawaiController::class, 'downloadQRCodes'])->name('pegawai.downloadQRCodes');

