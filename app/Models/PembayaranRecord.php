<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranRecord extends Model
{
    protected $fillable = [
        'pembayaran_id',
        'payment_method',
        'amount',
        'paid_at',
        'notes',
        'receipt_number',
        'proof_image',
        'proof_status',
        'proof_notes',
        'unique_code',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
