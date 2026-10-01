<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
// Pastikan kamu meng-import model lain jika namanya berbeda (misal: User, BilliardTable)

class AdminController extends Controller
{
    public function index()
    {
        // Menghitung total pesanan yang berstatus 'paid'
        $totalPaidBookings = Booking::where('status', 'paid')->count();
        
        // Menghitung total pendapatan dari pesanan yang 'paid'
        $totalRevenue = Booking::where('status', 'paid')->sum('total_price');
        
        // Mengambil 10 pesanan terbaru (beserta relasinya jika ada)
        // Hapus "->with(...)" jika kamu belum mengatur relasi di Model Booking
        $recentBookings = Booking::with('user')->orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact('totalPaidBookings', 'totalRevenue', 'recentBookings'));
    }
}