<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSesiController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PenyuluhController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PeternakController;
use App\Http\Controllers\TernakController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::prefix('admin')->middleware('admin:Admin')->group(function () {
    Route::get('/dashboard', [AdminSesiController::class, 'adminLoginView'])
        ->name('admin.dashboard');
});

Route::prefix('admin')->middleware('authenticated')->group(function () {
    Route::get('login', [AdminSesiController::class, 'adminLoginView'])->name('admin.login');
    Route::post('login', [AdminSesiController::class, 'login'])->name('admin.login.submit');
    Route::get('register', [AdminSesiController::class, 'registerView'])->name('admin.register');
    Route::post('register', [AdminSesiController::class, 'register'])->name('admin.register.submit');
});

Route::prefix('admin')->middleware('admin:Admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
});

// Peternak Route
Route::prefix('peternak')->middleware('admin:Peternak')->group(function () {

    // Ternak Route
    Route::get('/dashboard', [PeternakController::class, 'index'])
        ->name('dashboard');
    Route::get('/ternak', [TernakController::class, 'index'])
        ->name('ternak.index');
    Route::get('/ternak/tambah', [TernakController::class, 'ternakStoreView'])
        ->name('ternak.addView');
    Route::get('/ternak/{id}/detail', [TernakController::class, 'ternakDetail'])
        ->name('ternak.detailView');
    Route::delete('/ternak/{id}/delete', [TernakController::class, 'deleteTernak'])
        ->name('ternak.delete');
    Route::post('/ternak/add-ternak', [TernakController::class, 'store'])->name('ternak.store');

    // Konsultasi Ternak Route
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])
        ->name('konsultasi.index');
    Route::get('/konsultasi/tambah', [KonsultasiController::class, 'konsultasiStoreView'])
        ->name('konsultasi.addView');
    Route::post('/konsultasi/tambah-konsultasi', [KonsultasiController::class, 'store'])
        ->name('konsultasi.store');
    Route::delete('/konsultasi/{id}/hapus', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');


    // Kesehatan Route
    Route::get('/kesehatan', [PeternakController::class, 'kesehatan'])
        ->name('peternak.kesehatan');
});


Route::prefix('penyuluh')->middleware('admin:Penyuluh')->group(function () {
    // Dashboard Penyuluh
    Route::get('/dashboard', [PenyuluhController::class, 'index'])
        ->name('penyuluh.dashboard');

    // Laporan Kesehatan Penyuluh
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('penyuluh.laporan');
    Route::get('/laporan/tambah', [LaporanController::class, 'laporanStoreView'])
        ->name('laporan.addView');

    Route::get('/laporan/detail/{id}', [LaporanController::class, 'laporanDetailView'])
        ->name('laporan.detailView');

    Route::get('/laporan/update/{id}', [LaporanController::class, 'laporanEditView'])
        ->name('laporan.editView');

    Route::put('/laporan/edit{id}', [LaporanController::class, 'edit'])
        ->name('laporan.edit');

    Route::delete('/laporan/delete/{id}', [LaporanController::class, 'destroy'])
        ->name('laporan.destroy');

    Route::post('/laporan/add-laporan', [LaporanController::class, 'store'])->name('laporan.store');


    // Rekomendasi Pakan Penyuluh
    Route::get('/pakan', [PakanController::class, 'index'])
        ->name('penyuluh.pakan');
    Route::get('/pakan/tambah', [PakanController::class, 'pakanStoreView'])
        ->name('pakan.addView');
    Route::post('/pakan/add-pakan', [PakanController::class, 'store'])->name('pakan.store');

    Route::get('/pakan/detail/{id}', [PakanController::class, 'pakanDetailView'])
        ->name('pakan.detailView');
    Route::get('/pakan/update/{id}', [PakanController::class, 'pakanEditView'])
        ->name('pakan.editView');
    Route::put('/pakan/edit{id}', [PakanController::class, 'edit'])
        ->name('pakan.edit');
    Route::delete('/pakan/delete/{id}', [PakanController::class, 'destroy'])
        ->name('pakan.destroy');

    // Perawatan Penyuluh
    Route::get('/perawatan', [PerawatanController::class, 'index'])
        ->name('penyuluh.perawatan');
    Route::get('/perawatan/tambah', [PerawatanController::class, 'perawatanStoreView'])
        ->name('perawatan.addView');
    Route::post('/perawatan/add-perawatan', [PerawatanController::class, 'store'])->name('perawatan.store');


    Route::get('/perawatan/detail/{id}', [PerawatanController::class, 'perawatanDetailView'])
        ->name('perawatan.detailView');

    Route::get('/perawatan/update/{id}', [PerawatanController::class, 'perawatanEditView'])
        ->name('perawatan.editView');

    Route::put('/perawatan/edit{id}', [PerawatanController::class, 'edit'])
        ->name('perawatan.edit');

    Route::delete('/perawatan/delete/{id}', [PerawatanController::class, 'destroy'])
        ->name('perawatan.destroy');


    // Daftar Konsultasi
    Route::get('/konsultasi', [KonsultasiController::class, 'penyuluhView'])
        ->name('penyuluh.konsultasi');
    Route::get('/konsultasi/update/{id}', [KonsultasiController::class, 'konsultasiEditView'])
        ->name('konsultasi.editView');
    Route::put('/konsultasi/update-respon/{id}', [KonsultasiController::class, 'storeResponPenyuluh'])
        ->name('konsultasi.updateRespon');
});


Route::post('/logout', [AdminSesiController::class, 'logout'])->name('logout');
