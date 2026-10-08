<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'midtrans_order_id',
    'gross_amount',
    'payment_type',
    'transaction_status',
    'snap_token',
])]
class Payment extends Model
{
    use HasFactory;

    // Relasi: Pembayaran ini milik satu Booking tertentu
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}