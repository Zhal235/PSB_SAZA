<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BankSettingController;
use App\Http\Controllers\BuktiPembayaranController;
use App\Http\Controllers\CalonSantriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\PembayaranItemController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SantriController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        // Semua role redirect ke admin dashboard (akses diatur by permissions di sidebar)
        return redirect()->route('admin.dashboard');
    }
    // Jika belum login, redirect ke halaman login
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

// Admin Routes (accessible by admin and petugas roles based on permissions)
Route::middleware(['auth', 'role:admin,petugas_pendaftaran,petugas_keuangan'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Calon Santri Management
    Route::resource('calon-santri', CalonSantriController::class);
    Route::post('calon-santri/{calonSantri}/reset-password', [CalonSantriController::class, 'resetPassword'])->name('calon-santri.reset-password');
    Route::get('calon-santri-export', [CalonSantriController::class, 'export'])->name('calon-santri.export');

    // Pembayaran Items Management
    Route::resource('pembayaran-items', PembayaranItemController::class);

    // Bank Settings Management
    Route::resource('bank-settings', BankSettingController::class);

    // Financial Records Management
    Route::resource('financial-records', FinancialRecordController::class);

    // Pembayaran Management
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{pembayaran}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{pembayaran}/payment', [PembayaranController::class, 'storePayment'])->name('pembayaran.storePayment');
    Route::get('/pembayaran/{pembayaran}/invoice', [PembayaranController::class, 'invoice'])->name('pembayaran.invoice');
    Route::get('/pembayaran/{pembayaran}/invoice-pdf', [PembayaranController::class, 'invoicePdf'])->name('pembayaran.invoicePdf');

    // Bukti Pembayaran Management
    Route::get('/bukti-pembayaran', [BuktiPembayaranController::class, 'index'])->name('bukti-pembayaran.index');
    Route::get('/bukti-pembayaran/{bukti}', [BuktiPembayaranController::class, 'show'])->name('bukti-pembayaran.show');
    Route::post('/bukti-pembayaran/{bukti}/verify', [BuktiPembayaranController::class, 'verify'])->name('bukti-pembayaran.verify');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Dokumen Routes (moved inside admin group for consistency)
    Route::get('/verifikasi-dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/{calonSantri}/upload', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::post('/dokumen/{calonSantri}', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::delete('/dokumen/{dokumen}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');
});

// API Routes
Route::middleware(['auth', 'role:admin,petugas_pendaftaran,petugas_keuangan'])->prefix('api')->group(function () {
    Route::post('/dokumen/toggle-hardcopy', [DokumenController::class, 'toggleHardcopy']);
});

// Santri Routes
Route::middleware(['auth', 'role:calon_santri,santri'])->prefix('santri')->name('santri.')->group(function () {
    Route::get('/select-jenjang', [SantriController::class, 'selectJenjang'])->name('select-jenjang');
    Route::post('/save-jenjang', [SantriController::class, 'saveJenjang'])->name('save-jenjang');
    Route::get('/dashboard', [SantriController::class, 'dashboard'])->name('dashboard');
    Route::get('/form-pendaftaran', [SantriController::class, 'formPendaftaran'])->name('form-pendaftaran');
    Route::post('/save-data', [SantriController::class, 'savePendaftaran'])->name('save-data');
    Route::get('/pembayaran', [SantriController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/pembayaran/{pembayaran}/invoice', [SantriController::class, 'pembayaranInvoice'])->name('pembayaran-invoice');
    Route::get('/bukti-pendaftaran/{calonSantri}/print', [SantriController::class, 'printBuktiPendaftaran'])->name('print-bukti-pendaftaran');
    Route::get('/bukti-pendaftaran/{calonSantri}/download', [SantriController::class, 'downloadBuktiPendaftaran'])->name('download-bukti-pendaftaran');
    Route::post('/pembayaran/{pembayaran}/upload-bukti', [SantriController::class, 'uploadBuktiPembayaran'])->name('upload-bukti-pembayaran');
    Route::get('/dokumen-upload', [SantriController::class, 'dokumenUpload'])->name('dokumen-upload');
    Route::post('/dokumen-upload', [SantriController::class, 'dokumenStore'])->name('dokumen-store');
});