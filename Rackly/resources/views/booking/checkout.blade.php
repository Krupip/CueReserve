<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran DP (Uang Muka)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8 text-center">

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h3>
                    <p class="text-gray-500 mb-4">Selesaikan pembayaran DP agar jadwal mejamu terkunci.</p>

                    {{-- Countdown Timer --}}
                    <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-300 text-amber-700 rounded-lg px-5 py-3 mb-6 font-semibold text-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Sisa waktu pembayaran:
                        <span id="countdown" class="text-lg font-bold">15:00</span>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-8 inline-block w-full max-w-md border border-gray-200 text-left">
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Nomor Meja</span>
                            <span class="font-bold text-gray-900">{{ $booking->billiardTable->table_number }}</span>
                        </div>
                        <div class="flex justify-between mb-3">
                            <span class="text-gray-600">Jadwal</span>
                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                            <span class="font-bold text-gray-900">Total DP (30%)</span>
                            <span class="font-bold text-emerald-600 text-xl">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <button id="pay-button" class="w-full max-w-md bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 px-8 rounded-lg shadow-lg transition duration-200 text-lg">
                            Bayar Sekarang
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        // === Countdown Timer ===
        // Hitung sisa waktu berdasarkan waktu booking dibuat + 15 menit
        const bookingCreatedAt = new Date("{{ $booking->created_at->toIso8601String() }}").getTime();
        const TIMEOUT_MS = 15 * 60 * 1000;
        const expireAt = bookingCreatedAt + TIMEOUT_MS;
        const historyUrl = "{{ route('booking.history') }}";
        const countdownEl = document.getElementById('countdown');

        const timer = setInterval(function () {
            const now = new Date().getTime();
            const remaining = expireAt - now;

            if (remaining <= 0) {
                clearInterval(timer);
                countdownEl.textContent = '00:00';
                alert('Waktu pembayaran telah habis. Pesanan Anda otomatis dibatalkan.');
                window.location.href = historyUrl;
                return;
            }

            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
            countdownEl.textContent =
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

            // Warna merah jika kurang dari 3 menit
            if (remaining < 3 * 60 * 1000) {
                countdownEl.style.color = '#dc2626';
            }
        }, 1000);

        // === Midtrans Snap ===
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    clearInterval(timer);
                    window.location.href = "{{ route('booking.history') }}";
                },
                onPending: function (result) {
                    alert("Selesaikan pembayaranmu. Menutup jendela...");
                    window.location.href = "{{ route('dashboard') }}";
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    alert('Kamu menutup jendela sebelum menyelesaikan pembayaran.');
                }
            });
        };
    </script>
</x-app-layout>