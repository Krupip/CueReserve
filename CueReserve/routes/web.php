<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\BilliardTable;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Ambil semua data meja dari database
    $tables = BilliardTable::all(); 
    
    // Kirim data meja ke file dashboard.blade.php
    return view('dashboard', compact('tables'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Form Booking Meja
    Route::get('/book/{table}', [BookingController::class, 'create'])->name('book.create');
    Route::post('/book/{table}', [BookingController::class, 'store'])->name('book.store');
});

require __DIR__.'/auth.php';

// Route untuk Webhook Midtrans (Tanpa Middleware Auth)
Route::post('/webhook/midtrans', [BookingController::class, 'webhook'])->name('midtrans.webhook');
