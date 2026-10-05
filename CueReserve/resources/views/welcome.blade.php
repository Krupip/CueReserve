<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CueReserve - Booking Meja Biliar Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <!-- Navbar Sederhana -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-bold text-2xl text-blue-600">CueReserve</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium">Panel Admin</a>
                        @else
                            <a href="#" class="text-gray-600 hover:text-blue-600 font-medium">Riwayat Booking</a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-md text-sm font-medium transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl mb-4">
                Main Biliar Tanpa Antre
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                Booking meja biliar favoritmu di kampus sekarang. Cek ketersediaan dan bayar DP dengan mudah.
            </p>
        </div>
    </div>

    <!-- Katalog Meja -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Pilih Meja Kamu</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($tables as $table)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $table->table_number }}</h3>
                                <p class="text-sm text-gray-500 font-medium">{{ $table->type }} Table</p>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">Tersedia</span>
                        </div>
                        
                        <div class="mb-6">
                            <p class="text-3xl font-extrabold text-blue-600">
                                Rp {{ number_format($table->price_per_hour, 0, ',', '.') }}
                                <span class="text-sm text-gray-500 font-normal">/jam</span>
                            </p>
                        </div>

                        <!-- Tombol Booking (Route-nya belum kita buat, sementara diarahkan ke #) -->
                        <a href="{{ route('bookings.create', $table->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-sm">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500 text-lg">Wah, sepertinya semua meja sedang penuh atau maintenance.</p>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>