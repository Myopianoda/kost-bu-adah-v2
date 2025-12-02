<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PublicUnitController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SewaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PenyewaBookingController;
use App\Http\Controllers\Auth\PenyewaLoginController;
use App\Http\Controllers\Auth\PenyewaRegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. RUTE PUBLIK (Bisa diakses siapa saja)
// ====================================================

Route::get('/', function () {
    // Menampilkan unit tersedia/booking di halaman depan
    $unitTersedia = App\Models\Unit::whereIn('status', ['tersedia', 'booking'])
                        ->orderBy('name')
                        ->get();
    return view('welcome', compact('unitTersedia'));
});

// Detail Unit untuk Tamu
Route::get('/unit/{unit}', [PublicUnitController::class, 'show'])->name('public.unit.show');


// ====================================================
// 2. RUTE ADMINISTRATOR (Guard: web)
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Dashboard & Profil ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Laporan & Export (Wajib di atas Resource) ---
    Route::get('/penyewa/export', [PenyewaController::class, 'exportExcel'])->name('penyewa.export');
    Route::get('/pengeluaran/export', [PengeluaranController::class, 'exportExcel'])->name('pengeluaran.export');
    Route::get('/tagihan/export', [TagihanController::class, 'exportExcel'])->name('tagihan.export');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // --- Manajemen Master Data (CRUD) ---
    Route::resource('units', UnitController::class)->except(['show']);
    Route::resource('penyewa', PenyewaController::class)->except(['show']);
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    // --- Manajemen Transaksi Sewa ---
    Route::get('/sewa/create', [SewaController::class, 'create'])->name('sewa.create');
    Route::post('/sewa', [SewaController::class, 'store'])->name('sewa.store');
    Route::post('/sewa/{sewa}/stop', [SewaController::class, 'stop'])->name('sewa.stop');

    // --- Manajemen Booking (Approve/Reject) ---
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // --- Manajemen Keuangan (Verifikasi Tagihan) ---
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::patch('/tagihan/{tagihan}/konfirmasi', [TagihanController::class, 'konfirmasi'])->name('tagihan.konfirmasi');
    Route::patch('/tagihan/{tagihan}/tolak', [TagihanController::class, 'tolak'])->name('tagihan.tolak');
});


// ====================================================
// 3. RUTE PENYEWA (Guard: penyewa)
// ====================================================

// A. Tamu (Guest) - Login & Register
Route::middleware('guest:penyewa')->group(function () {
    Route::get('/penyewa/login', [PenyewaLoginController::class, 'create'])->name('penyewa.login');
    Route::post('/penyewa/login', [PenyewaLoginController::class, 'store'])->name('penyewa.login.store');
    
    Route::get('/penyewa/register', [PenyewaRegisterController::class, 'create'])->name('penyewa.register');
    Route::post('/penyewa/register', [PenyewaRegisterController::class, 'store'])->name('penyewa.register.store');
});

// B. Logout
Route::post('/penyewa/logout', [PenyewaLoginController::class, 'destroy'])
    ->middleware('auth:penyewa')
    ->name('penyewa.logout');

// C. Portal Penyewa (Harus Login)
Route::middleware(['auth:penyewa'])->prefix('portal')->name('penyewa.')->group(function () {
    // Dashboard & Profil
    Route::get('/dashboard', [PortalController::class, 'index'])->name('dashboard');
    Route::get('/profil', [PortalController::class, 'editProfil'])->name('profil.edit');
    Route::patch('/profil', [PortalController::class, 'updateProfil'])->name('profil.update');

    // Booking Unit
    Route::get('/booking/{unit}', [PenyewaBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{unit}', [PenyewaBookingController::class, 'store'])->name('booking.store');
});


// ====================================================
// 4. RUTE BERSAMA (Admin & Penyewa)
// ====================================================
Route::middleware(['auth:web,penyewa'])->group(function () {

    // Halaman Bayar & Upload Bukti
    Route::get('/tagihan/{tagihan}/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
    Route::patch('/tagihan/{tagihan}/upload', [TagihanController::class, 'upload'])->name('tagihan.upload');

    // [BARU] Download Nota PDF (Bisa diakses Admin & Penyewa)
    Route::get('/tagihan/{id}/nota', [TagihanController::class, 'downloadNota'])->name('tagihan.nota');

});

require __DIR__.'/auth.php';