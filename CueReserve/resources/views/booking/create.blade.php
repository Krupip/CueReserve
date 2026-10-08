<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $billiardTable->table_number }} - CueReserve</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk perhitungan harga real-time -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <!-- Navbar Sederhana -->
    <nav class="bg-white shadow mb-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('dashboard') }}" class="font-bold text-2xl text-blue-600">CueReserve</a>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium">&larr; Kembali ke Katalog</a>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

        <!-- Kartu Info Meja -->
        <div class="bg-blue-600 rounded-t-xl p-6 text-white">
            <h1 class="text-2xl font-bold mb-1">Booking {{ $billiardTable->table_number }}</h1>
            <p class="text-blue-100">{{ $billiardTable->type }} Table &bull; Rp {{ number_format($billiardTable->price_per_hour, 0, ',', '.') }}/jam</p>
        </div>

        <!-- Form Booking dengan Alpine.js -->
        <div class="bg-white shadow-md rounded-b-xl p-6 border border-t-0 border-gray-200"
            x-data="bookingCalculator({{ $billiardTable->price_per_hour }})">

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <!-- Kirim ID Meja secara sembunyi -->
                <input type="hidden" name="table_id" value="{{ $billiardTable->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Tanggal Main -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="booking_date">Tanggal Main</label>
                        <input type="date" name="booking_date" id="booking_date" required min="{{ date('Y-m-d') }}" x-model="selectedDate"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Jam Mulai -->
                    <div x-data="{ openStart: false }">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
                        <div class="relative">
                            <button type="button" @click="openStart = !openStart" @click.outside="openStart = false"
                                class="shadow border rounded w-full py-2 px-3 text-left text-gray-700 bg-white flex justify-between items-center">
                                <span x-text="startTime ? startTime : '-- Pilih Jam Mulai --'"></span>
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <ul x-show="openStart" style="display: none;" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto py-1 text-sm">
                                <template x-for="item in availableStartHours" :key="item.time">
                                    <!-- Jika jam mulai diubah, jam selesai otomatis di-reset agar tidak error -->
                                    <li @click="if(!item.isDisabled) { startTime = item.time; openStart = false; endTime = ''; }"
                                        class="select-none relative py-2 px-3"
                                        :class="{
                                            'cursor-not-allowed text-gray-400 bg-gray-50': item.isDisabled,
                                            'cursor-pointer hover:bg-blue-600 hover:text-white text-gray-900': !item.isDisabled,
                                            'bg-blue-50 text-blue-700 font-semibold': startTime === item.time && !item.isDisabled
                                        }"
                                        x-text="item.time">
                                    </li>
                                </template>
                                <li x-show="availableStartHours.length === 0" class="py-2 px-3 text-gray-500 italic text-center">Meja tutup / Waktu habis</li>
                            </ul>
                        </div>
                        <input type="hidden" name="start_time" x-model="startTime">
                    </div>

                    <!-- Jam Selesai -->
                    <div x-data="{ openEnd: false }">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jam Selesai</label>
                        <div class="relative">
                            <button type="button" @click="openEnd = !openEnd" @click.outside="openEnd = false" :disabled="!startTime"
                                class="shadow border rounded w-full py-2 px-3 text-left text-gray-700 bg-white flex justify-between items-center disabled:bg-gray-100 disabled:cursor-not-allowed">
                                <span x-text="endTime ? endTime : (startTime ? '-- Pilih Jam Selesai --' : 'Pilih Jam Mulai Dulu')"></span>
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <ul x-show="openEnd && startTime" style="display: none;" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto py-1 text-sm">
                                <template x-for="hour in availableEndHours" :key="hour">
                                    <li @click="endTime = hour; openEnd = false"
                                        class="cursor-pointer select-none relative py-2 px-3 hover:bg-blue-600 hover:text-white"
                                        :class="endTime === hour ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-900'"
                                        x-text="hour">
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <input type="hidden" name="end_time" x-model="endTime">
                    </div>
                </div>

                <!-- Ringkasan Pembayaran (Muncul Otomatis jika jam valid) -->
                <div x-show="isValidTime" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h3 class="font-bold text-gray-700 mb-2 border-b pb-2">Ringkasan Pembayaran</h3>
                    <div class="flex justify-between mb-1 text-sm text-gray-600">
                        <span>Durasi Bermain:</span>
                        <span x-text="hours + ' Jam'" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between mb-1 text-sm text-gray-600">
                        <span>Total Harga:</span>
                        <span x-text="formatRupiah(totalPrice)" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t font-bold text-blue-600 text-lg">
                        <span>DP yang harus dibayar (30%):</span>
                        <span x-text="formatRupiah(dp)"></span>
                    </div>
                </div>

                <!-- Peringatan jika jam salah -->
                <div x-show="!isValidTime && startTime && endTime" class="bg-red-50 text-red-600 p-3 rounded-md mb-6 text-sm">
                    Jam selesai harus lebih besar dari jam mulai.
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="!isValidTime"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-8 rounded-lg shadow transition duration-200">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Logika Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            const getTodayStr = () => {
                let d = new Date();
                return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            };

            Alpine.data('bookingCalculator', (price) => ({
                pricePerHour: price,
                selectedDate: getTodayStr(), // Default hari ini menggunakan waktu lokal browser
                today: getTodayStr(),
                currentHour: new Date().getHours(), // Jam sesuai browser user
                startTime: '',
                endTime: '',

                // Menghasilkan jam mulai beserta status disabled
                get availableStartHours() {
                    let hours = [];
                    let isToday = this.selectedDate === this.today;

                    for (let i = 8; i <= 22; i++) {
                        // Jika hari ini, jam yang lebih kecil atau sama dengan jam sekarang di-disable
                        let isDisabled = isToday && (i <= this.currentHour);
                        hours.push({
                            time: i.toString().padStart(2, '0') + ':00',
                            isDisabled: isDisabled
                        });
                    }
                    return hours;
                },

                // Menghasilkan jam selesai (selalu di atas jam mulai)
                get availableEndHours() {
                    let hours = [];
                    if (!this.startTime) return hours;

                    let start = parseInt(this.startTime.split(':')[0]);
                    for (let i = start + 1; i <= 23; i++) {
                        hours.push(i.toString().padStart(2, '0') + ':00');
                    }
                    return hours;
                },

                get hours() {
                    if (!this.startTime || !this.endTime) return 0;
                    let start = new Date(`2000-01-01T${this.startTime}`);
                    let end = new Date(`2000-01-01T${this.endTime}`);
                    return (end - start) / 3600000;
                },

                get isValidTime() {
                    return this.hours > 0;
                },
                get totalPrice() {
                    return this.hours * this.pricePerHour;
                },
                get dp() {
                    return this.totalPrice * 0.3;
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                }
            }))
        })
    </script>
</body>

</html>