<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSesiController;
use App\Http\Controllers\PenyuluhController;
use App\Http\Controllers\PeternakController;
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


Route::prefix('peternak')->middleware('admin:Peternak')->group(function () {
    Route::get('/dashboard', [PeternakController::class, 'index'])
        ->name('peternak.dashboard');
});


Route::prefix('penyuluh')->middleware('admin:Penyuluh')->group(function () {
    Route::get('/dashboard', [PenyuluhController::class, 'index'])
        ->name('penyuluh.dashboard');
});


Route::post('/logout', [AdminSesiController::class, 'logout'])->name('logout');
