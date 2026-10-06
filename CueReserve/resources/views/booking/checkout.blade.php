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
                    <p class="text-gray-500 mb-8">Selesaikan pembayaran DP agar jadwal mejamu terkunci.</p>
                    
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
                        <!-- Tombol untuk memunculkan pop-up Midtrans -->
                        <button id="pay-button" class="w-full max-w-md bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 px-8 rounded-lg shadow-lg transition duration-200 text-lg">
                            Bayar Sekarang
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script Wajib Midtrans -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // Memanggil pop-up Snap menggunakan token dari Controller
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Arahkan ke booking history jika sukses
                    window.location.href = "{{ route('booking.history') }}";
                },
                onPending: function(result){
                    // Bisa diarahkan ke halaman "Menunggu Pembayaran" jika punya, 
                    // atau kembali ke dashboard
                    alert("Selesaikan pembayaranmu. Menutup jendela...");
                    window.location.href = "{{ route('dashboard') }}";
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    // Jika user klik tombol silang / tutup pop-up
                    alert('Kamu menutup jendela sebelum menyelesaikan pembayaran.');
                }
            });
        };
    </script>
</x-app-layout>