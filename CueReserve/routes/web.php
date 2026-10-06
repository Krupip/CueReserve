<?php

use App\Http\Controllers\AdminBilliardTableController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\BilliardTable;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\HomeController;

// Rute Beranda
Route::get('/', [HomeController::class, 'index'])->name('dashboard');

// Rute Riwayat Booking User
Route::get('/booking-history', function () {
    $bookings = \App\Models\Booking::with('billiardTable')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
        
    return view('booking_history', compact('bookings'));
})->middleware(['auth', 'verified'])->name('booking.history');

Route::middleware('auth')->group(function () {
    // Rute untuk menampilkan form booking, dengan membawa ID meja yang dipilih
    Route::get('/booking/{billiardTable}', [BookingController::class, 'create'])->name('bookings.create');

    // Rute untuk memproses form booking (nanti kita buat fungsinya)
    Route::post('/booking', [BookingController::class, 'store'])->name('bookings.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Form Booking Meja
    Route::get('/book/{table}', [BookingController::class, 'create'])->name('book.create');
    Route::post('/book/{table}', [BookingController::class, 'store'])->name('book.store');

    // Rute Admin Page
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/tables', AdminBilliardTableController::class);
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware(['auth', IsAdmin::class])
        ->name('admin.dashboard');
});

require __DIR__ . '/auth.php';

// Route untuk Webhook Midtrans (Tanpa Middleware Auth)
Route::post('/webhook/midtrans', [BookingController::class, 'webhook'])->name('midtrans.webhook');
