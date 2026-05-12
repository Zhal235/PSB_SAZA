<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranItemDetail extends Model
{
    protected $fillable = [
        'pembayaran_id',
        'pembayaran_item_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function pembayaranItem()
    {
        return $this->belongsTo(PembayaranItem::class);
    }
}
