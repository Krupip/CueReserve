@extends('layouts.admin')

@section('title', 'Pengaturan - Admin CueReserve')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pengaturan Sistem</h1>
    <p class="text-gray-500 mb-8">Kelola konfigurasi umum aplikasi CueReserve.</p>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-8 max-w-xl">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-6">
                <label for="dp_percentage" class="block text-sm font-bold text-gray-700 mb-2">
                    Persentase Down Payment (DP)
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Jumlah yang harus dibayar user di muka saat memesan meja. Contoh: isi <strong>30</strong> untuk DP sebesar 30% dari total harga.
                </p>
                <div class="flex items-center gap-3">
                    <input
                        type="number"
                        id="dp_percentage"
                        name="dp_percentage"
                        value="{{ old('dp_percentage', $settings->get('dp_percentage')?->value ?? 30) }}"
                        min="1"
                        max="100"
                        class="w-32 border border-gray-300 rounded-lg px-4 py-2 text-gray-900 text-lg font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('dp_percentage') border-red-500 @enderror"
                    >
                    <span class="text-2xl text-gray-500 font-bold">%</span>
                </div>

                @error('dp_percentage')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- Preview kalkulasi --}}
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100 text-sm text-blue-800">
                    <strong>Contoh:</strong> Jika harga sewa meja adalah <strong>Rp 100.000/jam</strong>
                    dan durasi bermain <strong>2 jam</strong>, maka total harga adalah <strong>Rp 200.000</strong>,
                    dan DP yang harus dibayar adalah
                    <strong id="dp-preview">
                        Rp {{ number_format(200000 * (($settings->get('dp_percentage')?->value ?? 30) / 100), 0, ',', '.') }}
                    </strong>
                    (<span id="dp-pct-preview">{{ $settings->get('dp_percentage')?->value ?? 30 }}</span>%).
                </div>
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg transition duration-200 shadow">
                Simpan Pengaturan
            </button>
        </form>
    </div>

    <script>
        // Update preview kalkulasi saat input berubah
        const input = document.getElementById('dp_percentage');
        const previewRp = document.getElementById('dp-preview');
        const previewPct = document.getElementById('dp-pct-preview');
        const BASE_PRICE = 200000;

        input.addEventListener('input', function () {
            const pct = Math.min(100, Math.max(0, parseInt(this.value) || 0));
            const dp = BASE_PRICE * (pct / 100);
            previewRp.textContent = 'Rp ' + dp.toLocaleString('id-ID');
            previewPct.textContent = pct;
        });
    </script>
@endsection
