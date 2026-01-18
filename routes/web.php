<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalonSantriController;
use App\Http\Controllers\DokumenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Calon Santri Management
    Route::resource('calon-santri', CalonSantriController::class);
});

// Dokumen Upload Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/verifikasi-dokumen', [DokumenController::class, 'index'])->name('verifikasi-dokumen.index');
    Route::get('/dokumen/{calonSantri}/upload', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen/{calonSantri}', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
});

// API Routes
Route::middleware(['auth', 'role:admin'])->prefix('api')->group(function () {
    Route::post('/dokumen/toggle-hardcopy', [DokumenController::class, 'toggleHardcopy']);
});

// Santri Routes
Route::middleware(['auth', 'role:calon_santri'])->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', function () {
        return view('santri.dashboard');
    })->name('dashboard');
});
