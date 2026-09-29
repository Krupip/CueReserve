<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Meja Biliard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Grid Layout untuk Card Meja -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @foreach ($tables as $table)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6 flex flex-col items-center text-center">
                            
                            <!-- Nomor Meja -->
                            <div class="text-4xl font-extrabold text-gray-900 mb-2">
                                {{ $table->table_number }}
                            </div>
                            
                            <!-- Tipe Meja -->
                            <div class="text-sm font-medium text-gray-500 uppercase tracking-widest mb-4">
                                {{ $table->type }}
                            </div>
                            
                            <!-- Harga -->
                            <div class="text-2xl font-bold text-emerald-600 mb-6">
                                Rp {{ number_format($table->price_per_hour, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">/ jam</span>
                            </div>

                            <!-- Tombol Booking -->
                            <a href="#" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-150 ease-in-out text-center">
                                Pilih Jadwal
                            </a>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>