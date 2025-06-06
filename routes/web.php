<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function() {
    Route::get('/',[AppController::class, 'login'])->name('login');
    Route::post('auth',[AppController::class, 'auth']);
});

Route::middleware('auth')->group(function() {
    Route::post('logout', [AppController::class, 'logout']);
    Route::get('dashboard', [AppController::class, 'dashboard']);
    Route::get('my_profile', [ProfileController::class, 'index']);
    Route::post('my_profile', [ProfileController::class, 'update']);
});

