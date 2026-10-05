@extends('layouts.admin')

@section('title', 'Kelola Meja - Admin CueReserve')

@section('content')
<!-- Header & Tombol Tambah -->
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Kelola Data Meja</h1>

    <a href="{{ route('tables.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow inline-block">
        + Tambah Meja Baru
    </a>
</div>

<!-- Alert Sukses -->
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
    {{ session('success') }}
</div>
@endif

<!-- Tabel Data Meja -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Meja</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/Jam</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($tables as $table)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $table->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $table->table_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($table->price_per_hour, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($table->is_active == 1)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                    @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Maintenance</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    
                    <!-- Tombol Edit -->
                    <a href="{{ route('tables.edit', $table->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition duration-150 mr-2">Edit</a>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('tables.destroy', $table->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus meja ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition duration-150">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data meja.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection