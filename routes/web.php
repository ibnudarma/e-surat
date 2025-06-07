<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;

// Group Guest
Route::middleware('guest')->group(function() {
    Route::get('/',[AppController::class, 'login'])->name('login');
    Route::post('auth',[AppController::class, 'auth']);
});

// Group Auth
Route::middleware('auth')->group(function() {
    Route::post('logout', [AppController::class, 'logout']);
    Route::get('dashboard', [AppController::class, 'dashboard']);
    Route::get('my_profile', [ProfileController::class, 'index']);
    Route::post('my_profile', [ProfileController::class, 'update']);

    // Surat Keluar
    Route::get('surat_keluar', [SuratKeluarController::class, 'index']);
    Route::get('surat_keluar/create', [SuratKeluarController::class, 'create']);
    Route::post('surat_keluar', [SuratKeluarController::class, 'store']);

    // Surat Masuk
    Route::get('surat_masuk', [SuratMasukController::class, 'index']);
});

