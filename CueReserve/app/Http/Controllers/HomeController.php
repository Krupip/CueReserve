<?php

namespace App\Http\Controllers;

use App\Models\BilliardTable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hanya tampilkan meja yang statusnya aktif/tersedia
        $tables = BilliardTable::where('is_active', 1)->get();
        return view('home', compact('tables'));
    }
}
