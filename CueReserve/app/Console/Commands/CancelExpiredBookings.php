<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'booking:cancel-expired';

    /**
     * The console command description.
     */
    protected $description = 'Membatalkan otomatis booking pending yang sudah melebihi 15 menit tanpa pembayaran.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Temukan semua booking pending yang dibuat lebih dari 15 menit yang lalu
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();

        $count = $expiredBookings->count();

        foreach ($expiredBookings as $booking) {
            // Batalkan booking
            $booking->update(['status' => 'cancelled']);

            // Batalkan juga data payment terkait (jika ada)
            if ($booking->payment) {
                $booking->payment->update(['transaction_status' => 'expire']);
            }
        }

        if ($count > 0) {
            $this->info("Berhasil membatalkan {$count} booking yang kadaluarsa.");
        } else {
            $this->info('Tidak ada booking yang kadaluarsa saat ini.');
        }
    }
}
