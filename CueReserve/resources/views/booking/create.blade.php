<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesan Meja') }} {{ $table->table_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">

                    <!-- Informasi Meja -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-900">Meja {{ $table->table_number }}</h3>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mt-1">{{ $table->type }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Tarif per jam</p>
                            <p class="text-xl font-bold text-emerald-600">Rp {{ number_format($table->price_per_hour, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Menampilkan Pesan Error (jika validasi waktu salah nantinya) -->
                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Form Input Waktu -->
                    <form method="POST" action="{{ route('book.store', $table->id) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Input Tanggal -->
                            <div>
                                <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Main</label>
                                <input type="date" name="booking_date" id="booking_date" min="{{ date('Y-m-d') }}"
                                    class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                            </div>

                            <!-- Input Waktu Mulai -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                <select name="start_time" id="start_time" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Pilih Jam Mulai</option>
                                    @for ($i = 8; $i <= 23; $i++)
                                        @php $time=sprintf('%02d:00', $i); @endphp
                                        <option value="{{ $time }}">{{ $time }}</option>
                                        @endfor
                                </select>
                            </div>

                            <!-- Input Waktu Selesai -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                                <select name="end_time" id="end_time" class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                    <option value="" disabled selected>Pilih Jam Selesai</option>
                                    @for ($i = 8; $i <= 23; $i++)
                                        @php $time=sprintf('%02d:00', $i); @endphp
                                        <option value="{{ $time }}">{{ $time }}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-900 font-medium mr-6 transition">Batal</a>
                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold py-2.5 px-6 rounded-lg transition duration-150">
                                Konfirmasi & Bayar DP
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>