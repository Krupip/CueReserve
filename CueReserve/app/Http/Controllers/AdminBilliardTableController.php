<?php

namespace App\Http\Controllers;

use App\Models\BilliardTable;
use Illuminate\Http\Request;

class AdminBilliardTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = BilliardTable::all();
        return view('admin.tables.index', compact('tables'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan agar tidak ada yang kosong atau salah format
        $request->validate([
            'table_number' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price_per_hour' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        // 2. Simpan ke database
        \App\Models\BilliardTable::create($request->all());

        // 3. Kembalikan ke halaman daftar meja dengan pesan sukses
        return redirect()->route('tables.index')->with('success', 'Meja baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = \App\Models\BilliardTable::findOrFail($id);

        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Meja berhasil dihapus!');
    }
}
