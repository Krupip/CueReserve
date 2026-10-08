<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    // Tampilkan halaman pengaturan
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }

    // Simpan perubahan pengaturan
    public function update(Request $request)
    {
        $request->validate([
            'dp_percentage' => 'required|integer|min:1|max:100',
        ], [
            'dp_percentage.required' => 'Persentase DP wajib diisi.',
            'dp_percentage.integer'  => 'Persentase DP harus berupa angka bulat.',
            'dp_percentage.min'      => 'Persentase DP minimal 1%.',
            'dp_percentage.max'      => 'Persentase DP maksimal 100%.',
        ]);

        Setting::where('key', 'dp_percentage')
            ->update(['value' => $request->dp_percentage]);

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
