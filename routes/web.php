<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AsdaController;
use App\Http\Controllers\KabagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SekdaController;
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
    Route::get('surat_keluar/{id}', [SuratKeluarController::class, 'show']);
    Route::get('surat_keluar_create', [SuratKeluarController::class, 'create']);
    Route::post('surat_keluar', [SuratKeluarController::class, 'store']);
    Route::get('surat_keluar/{id}/edit', [SuratKeluarController::class, 'edit']);
    Route::put('surat_keluar/{id}', [SuratKeluarController::class, 'update']);

    // Surat Masuk
    Route::get('surat_masuk', [SuratMasukController::class, 'index']);
    Route::get('surat_masuk/{id}', [SuratMasukController::class, 'show']);
    Route::get('surat_masuk/balas/{id}', [SuratMasukController::class, 'reply']);
    Route::post('surat_masuk/balas', [SuratMasukController::class, 'replyStore']);
    Route::get('surat_masuk/diterima/{id}', [SuratMasukController::class, 'diterima']);

    // Kabag
    Route::get('kabag/kartu_disposisi/{id}', [KabagController::class, 'kartuDisposisiCreate']);
    Route::post('kabag/kartu_disposisi', [KabagController::class, 'kartuDisposisiStore']);
    Route::post('kabag/kartu_disposisi_view/{id}', [KabagController::class, 'kartuDisposisiStore']);

    // Disposisi Sekda
    Route::get('disposisi/sekda/{id}', [SekdaController::class, 'disposisiCreate']);
    Route::post('disposisi/sekda', [SekdaController::class, 'disposisiStore']);

    // Asda
    Route::get('asda/disposisi_sekda', [AsdaController::class, 'disposisiSekda']);
});
