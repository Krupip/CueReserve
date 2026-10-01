<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Meja - Admin CueReserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header & Tombol Tambah -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Data Meja</h1>

            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 mr-4 font-medium">
                &larr; Kembali ke Dashboard
            </a>

            <!-- Tombol Tambah Meja (Nanti kita fungsikan di Bagian 2) -->
            <a href="{{ route('tables.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                + Tambah Meja Baru
            </a>
        </div>

        <!-- Alert Sukses (Jika ada pesan sukses dari controller) -->
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
                        <!-- Menggunakan table_number -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $table->table_number }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($table->price_per_hour, 0, ',', '.') }}</td>

                        <!-- Menggunakan is_active (1 = true, 0 = false) -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($table->is_active == 1)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Maintenance</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">

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
    </div>

</body>

</html>