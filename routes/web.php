<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSesiController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\InformasiPakanController;
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
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/kelola-user', [AdminController::class, 'userView'])
        ->name('admin.userView');
    Route::post('/store-user', [AdminController::class, 'storeUser'])
        ->name('user.store');
    Route::delete('/users/{idUser}', [AdminController::class, 'deleteUser'])
        ->name('user.delete');
    Route::put('/users/{idUser}/update', [AdminController::class, 'updateUser'])
        ->name('user.update');
    Route::get('/ternak', [AdminController::class, 'ternak'])
        ->name('admin.ternak');
    Route::get('/informasi', [AdminController::class, 'informasi'])
        ->name('admin.informasi');
    Route::post('/store-informasi', [InformasiController::class, 'store'])
        ->name('informasi.store');
    Route::get('/pakan', [AdminController::class, 'pakan'])
        ->name('admin.pakan');
    Route::post('/store-pakan', [InformasiPakanController::class, 'store'])
        ->name('pakan.store');
    Route::delete('pakan/{idPakan}', [InformasiPakanController::class, 'destroy'])->name('pakan.destroy');
    Route::put('pakan/{idPakan}/update', [InformasiPakanController::class, 'update'])->name('pakan.update');
    Route::delete('informasi/{idInformasi}', [InformasiController::class, 'destroy'])->name('informasi.destroy');
    Route::put('informasi/{idInformasi}/update', [InformasiController::class, 'update'])->name('informasi.update');
});

Route::prefix('admin')->middleware('authenticated')->group(function () {
    Route::get('login', [AdminSesiController::class, 'adminLoginView'])->name('admin.login');
    Route::post('login', [AdminSesiController::class, 'login'])->name('admin.login.submit');
    Route::get('register', [AdminSesiController::class, 'registerView'])->name('admin.register');
    Route::post('register', [AdminSesiController::class, 'register'])->name('admin.register.submit');
});


// Perbaikan Routes untuk Peternak
Route::prefix('peternak')->middleware('admin:Peternak')->group(function () {

    // Dashboard Route
    Route::get('/dashboard', [PeternakController::class, 'index'])
        ->name('peternak.dashboard');

    // Ternak Routes - RESTful Resource Routes
    Route::get('/ternak', [TernakController::class, 'index'])
        ->name('peternak.ternak');

    Route::get('/ternak/create', [TernakController::class, 'create'])
        ->name('ternak.create');

    Route::post('/ternak', [TernakController::class, 'store'])
        ->name('ternak.store');

    Route::get('/ternak/{id}', [TernakController::class, 'show'])
        ->name('ternak.show');

    Route::get('/ternak/{id}/edit', [TernakController::class, 'edit'])
        ->name('ternak.edit');

    Route::put('/ternak/{id}', [TernakController::class, 'update'])
        ->name('ternak.update');

    Route::delete('/ternak/{id}', [TernakController::class, 'destroy'])
        ->name('ternak.destroy');

    // Additional Ternak Routes
    Route::get('/ternak/{id}/data', [TernakController::class, 'getTernakData'])
        ->name('ternak.getData');

    Route::get('/ternak/export', [TernakController::class, 'export'])
        ->name('ternak.export');

    // Konsultasi Routes - RESTful Resource Routes
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])
        ->name('peternak.konsultasi');;

    Route::post('/konsultasi', [KonsultasiController::class, 'store'])
        ->name('konsultasi.store');

    Route::get('/konsultasi/{id}', [KonsultasiController::class, 'show'])
        ->name('konsultasi.show');

    Route::delete('/konsultasi/{id}', [KonsultasiController::class, 'destroy'])
        ->name('konsultasi.destroy');

    // Kesehatan Route
    Route::get('/kesehatan', [PeternakController::class, 'kesehatan'])
        ->name('peternak.kesehatan');

    // Laporan Route
    Route::get('/laporan', [PeternakController::class, 'laporan'])
        ->name('peternak.laporan');

    // Profile Route
    Route::get('/profile', [PeternakController::class, 'profile'])
        ->name('peternak.profile');

    Route::put('/profile', [PeternakController::class, 'updateProfile'])
        ->name('peternak.profile.update');
});

// Alternative: Menggunakan Resource Route (lebih clean)
Route::prefix('peternak')->middleware('admin:Peternak')->group(function () {

    // Dashboard Route
    Route::get('/dashboard', [PeternakController::class, 'index'])
        ->name('peternak.dashboard');

    // Pakan Route
    Route::get('/pakan', [PeternakController::class, 'pakan'])
        ->name('peternak.pakan');


    // Ternak Resource Routes
    Route::resource('ternak', TernakController::class)->except(['create', 'edit']);

    // Override route names untuk konsistensi dengan blade template
    Route::get('/ternak', [TernakController::class, 'index'])
        ->name('peternak.ternak');

    // Additional Ternak Routes
    Route::get('/ternak/{id}/data', [TernakController::class, 'getTernakData'])
        ->name('ternak.getData');

    Route::post('/ternak/export', [TernakController::class, 'export'])
        ->name('ternak.export');

    // Konsultasi Resource Routes
    Route::resource('konsultasi', KonsultasiController::class)->only(['index', 'store', 'show', 'destroy']);
    // Override route names untuk konsistensi
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])
        ->name('peternak.konsultasi');

    // Other Routes
    Route::get('/kesehatan', [PeternakController::class, 'kesehatan'])
        ->name('peternak.kesehatan');

    Route::get('/laporan', [PeternakController::class, 'laporan'])
        ->name('peternak.laporan');

    Route::get('/profile', [PeternakController::class, 'profile'])
        ->name('peternak.profile');

    Route::put('/profile', [PeternakController::class, 'updateProfile'])
        ->name('peternak.profile.update');
});


Route::prefix('penyuluh')->middleware('admin:Penyuluh')->group(function () {
    // Dashboard Penyuluh
    Route::get('/dashboard', [PenyuluhController::class, 'index'])
        ->name('penyuluh.dashboard');

    // Daftar ternak route
    Route::get('/ternak', [PenyuluhController::class, 'ternak'])
        ->name('penyuluh.ternak');


    // Profil Route
    Route::get('/profile', [PenyuluhController::class, 'profile'])
        ->name('penyuluh.profile');

    // Ternak Route
    Route::get('/ternak', [PenyuluhController::class, 'ternak'])
        ->name('penyuluh.ternak');


    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('penyuluh.laporan');
    Route::post('/laporan/store', [LaporanController::class, 'store'])
        ->name('penyuluh.laporan.store');
    Route::get('/laporan/{id}/detail', [LaporanController::class, 'getDetail'])
        ->name('show.laporan');
    Route::get('laporan/{id}/edit', [LaporanController::class, 'edit'])->name('edit');
    Route::get('laporan/{id}/print', [LaporanController::class, 'printReport'])->name('print');
    Route::get('laporan/{id}/pdf', [LaporanController::class, 'exportPdf'])->name('pdf');
    Route::delete('laporan/{id}', [LaporanController::class, 'destroy'])->name('destroy');
    Route::post('laporan/bulk-delete', [LaporanController::class, 'bulkDestroy'])->name('bulk-destroy');



    // Rekomendasi Pakan Penyuluh
    Route::get('/pakan', [PakanController::class, 'index'])
        ->name('penyuluh.pakan');
    Route::post('/pakan/add-pakan', [PakanController::class, 'store'])->name('pakan.store');

    Route::get('/pakan/detail/{id}', [PakanController::class, 'pakanDetailView'])
        ->name('pakan.detailView');
    Route::get('/pakan/update/{id}', [PakanController::class, 'pakanEditView'])
        ->name('pakan.editView');
    Route::put('/pakan/edit/{id}', [PakanController::class, 'edit'])
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
    Route::get('/konsultasi/{id}/detail', [KonsultasiController::class, 'getDetail'])
        ->name('penyuluh.ambilDetail');
    Route::put('/konsultasi/update-respon/{id}', [KonsultasiController::class, 'storeResponPenyuluh'])
        ->name('konsultasi.updateRespon');
    Route::put('/konsultasi/update-status/{id}', [KonsultasiController::class, 'updateStatus'])
        ->name('konsultasi.updateStatus');
});


Route::post('/logout', [AdminSesiController::class, 'logout'])->name('logout');


if (app()->environment('local')) {
    Route::get('/test-errors/{code}', function ($code) {
        switch ($code) {
            case '401':
                abort(401);
            case '403':
                abort(403);
            case '404':
                abort(404);
            case '500':
                abort(500);
            default:
                abort(404);
        }
    });
}

Route::get('/unauthorized', function () {
    abort(403);
})->name('unauthorized');

Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
Route::get('/informasi/{idInformasi}', [InformasiController::class, 'show'])->name('informasi.show');
Route::get('/pakan', [InformasiPakanController::class, 'index'])->name('pakan.index');
Route::get('/pakan/{idPakan}', [InformasiPakanController::class, 'show'])->name('public.pakan.show');
Route::get('/informasi/{idInformasi}', [InformasiController::class, 'show'])->name('public.informasi.show');
