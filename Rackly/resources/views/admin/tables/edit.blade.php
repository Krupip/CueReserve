@extends('layouts.admin')

@section('title', 'Edit Meja - Admin CueReserve')

@section('content')
    <div class="flex items-center mb-6">
        <a href="{{ route('tables.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mr-4">
            &larr; Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Data Meja</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden p-6 max-w-3xl">
        <!-- Perhatikan action mengarah ke update dan method POST ditambah @method('PUT') -->
        <form action="{{ route('tables.update', $table->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Nama / Nomor Meja -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="table_number">Nama / Nomor Meja</label>
                <input type="text" name="table_number" id="table_number" value="{{ $table->table_number }}" required 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Tipe Meja -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Tipe Meja</label>
                <select name="type" id="type" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                    <option value="Pool" {{ $table->type == 'Pool' ? 'selected' : '' }}>Pool</option>
                    <option value="Snooker" {{ $table->type == 'Snooker' ? 'selected' : '' }}>Snooker</option>
                    <option value="Carom" {{ $table->type == 'Carom' ? 'selected' : '' }}>Carom</option>
                </select>
            </div>

            <!-- Harga Per Jam -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price_per_hour">Harga Sewa Per Jam (Rp)</label>
                <input type="number" name="price_per_hour" id="price_per_hour" value="{{ $table->price_per_hour }}" required min="0" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="is_active">Status Meja</label>
                <select name="is_active" id="is_active" required 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                    <option value="1" {{ $table->is_active == 1 ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ $table->is_active == 0 ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div class="flex items-center justify-end mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">
                    Update Data
                </button>
            </div>
        </form>
    </div>
@endsection