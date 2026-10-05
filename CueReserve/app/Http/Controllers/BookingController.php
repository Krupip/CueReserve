<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BilliardTable;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    // Menampilkan form booking untuk meja tertentu
    public function create(BilliardTable $billiardTable)
    {
        // Pastikan meja yang diakses benar-benar sedang aktif
        if (!$billiardTable->is_active) {
            return redirect('/')->with('error', 'Maaf, meja ini sedang maintenance.');
        }

        return view('booking.create', compact('billiardTable'));
    }

    public function store(Request $request, BilliardTable $table)
    {
        // 1. Validasi Input Terpisah (Hanya terima jam bulat)
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:00',
            'end_time' => 'required|date_format:H:00|after:start_time',
        ], [
            // Pesan error kustom jika formatnya salah
            'start_time.date_format' => 'Jam mulai harus berupa jam bulat (contoh: 13:00).',
            'end_time.date_format' => 'Jam selesai harus berupa jam bulat (contoh: 15:00).',
        ]);

        // Gabungkan tanggal dan jam menjadi format DateTime utuh
        $start = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->booking_date . ' ' . $request->end_time);

        // 2. Cek Jadwal Bentrok (Mencegah Double Booking)
        $isConflict = Booking::where('billiard_table_id', $table->id)
            ->whereIn('status', ['pending', 'paid'])
            ->where(function ($query) use ($start, $end) {
                // Logika Estafet: Izinkan booking jika jam mulai = jam selesai orang lain
                $query->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })->exists();

        if ($isConflict) {
            return back()->withErrors(['Jadwal ini sudah dipesan orang lain. Silakan pilih jam lain.'])->withInput();
        }

        // 3. Hitung Durasi dan Harga (Dihitung per menit agar presisi misal 1.5 jam)
        $durationHours = $start->diffInMinutes($end) / 60;
        $totalPrice = $durationHours * $table->price_per_hour;

        // Asumsi aturan bisnis CueReserve: DP dibayar 30% dari total
        $dpAmount = $totalPrice * 0.3;

        // 4. Simpan ke database
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'billiard_table_id' => $table->id,
            'start_time' => $start,
            'end_time' => $end,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'status' => 'pending',
        ]);

        // ... (kode insert ke tabel Booking sebelumnya tetap ada di atas ini)

        // 5. Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        // Membuat ID Order unik (Contoh: DP-1-1700000000)
        $orderId = 'DP-' . $booking->id . '-' . time();

        // 6. Simpan data ke tabel payments
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'midtrans_order_id' => $orderId,
            'gross_amount' => $dpAmount,
            'transaction_status' => 'pending',
        ]);

        // 7. Siapkan parameter untuk dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $dpAmount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        // 8. Dapatkan Snap Token dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // 9. Arahkan ke halaman Checkout
        return view('booking.checkout', compact('booking', 'payment', 'snapToken'));
    }

    public function webhook(Request $request)
    {
        // 1. Ambil Server Key dari .env untuk gembok keamanan
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // 2. Buat rumus validasi (Signature Key)
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // 3. Pastikan pesan ini benar-benar datang dari Midtrans (bukan penyusup)
        if ($hashed == $request->signature_key) {

            $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

            if ($payment) {
                $transactionStatus = $request->transaction_status;

                // Jika status sukses (settlement/capture)
                if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                    $payment->update(['transaction_status' => 'paid']);
                    $payment->booking->update(['status' => 'paid']);
                }
                // Jika dibatalkan atau kadaluarsa
                else if ($transactionStatus == 'cancel' || $transactionStatus == 'expire' || $transactionStatus == 'deny') {
                    $payment->update(['transaction_status' => 'failed']);
                    $payment->booking->update(['status' => 'cancelled']);
                }
            }
        }

        return response()->json(['message' => 'Webhook handled successfully']);
    }
}
