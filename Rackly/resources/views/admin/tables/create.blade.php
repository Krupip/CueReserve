<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Meja - Admin CueReserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    @extends('layouts.admin')

    @section('title', 'Tambah Meja - Admin CueReserve')

    @section('content')
    <div class="flex items-center mb-6">
        <a href="{{ route('tables.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mr-4">
            &larr; Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Tambah Meja Baru</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden p-6 max-w-3xl">
        <form action="{{ route('tables.store') }}" method="POST">
            @csrf

            <!-- Nama / Nomor Meja -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="table_number">Nama / Nomor Meja</label>
                <input type="text" name="table_number" id="table_number" required placeholder="Contoh: Meja 04"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Tipe Meja -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Tipe Meja</label>
                <select name="type" id="type" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                    <option value="Pool">Pool</option>
                    <option value="Snooker">Snooker</option>
                    <option value="Carom">Carom</option>
                </select>
            </div>

            <!-- Harga Per Jam -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price_per_hour">Harga Sewa Per Jam (Rp)</label>
                <input type="number" name="price_per_hour" id="price_per_hour" required min="0" placeholder="Contoh: 50000"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="is_active">Status Meja</label>
                <select name="is_active" id="is_active" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                    <option value="1">Tersedia</option>
                    <option value="0">Maintenance</option>
                </select>
            </div>

            <div class="flex items-center justify-end mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
    @endsection
</body>

</html>