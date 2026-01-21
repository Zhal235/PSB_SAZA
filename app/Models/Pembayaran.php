<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'calon_santri_id',
        'status',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'due_date',
        'unique_code',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function calonSantri()
    {
        return $this->belongsTo(CalonSantri::class);
    }

    public function records()
    {
        return $this->hasMany(PembayaranRecord::class);
    }

    // Generate unique code untuk nominal transfer (2000-3000)
    public static function generateUniqueCode()
    {
        do {
            $code = str_pad(mt_rand(2000, 3000), 4, '0', STR_PAD_LEFT);
        } while (self::where('unique_code', $code)->exists());
        
        return $code;
    }

    // Update status otomatis
    public function updateStatus()
    {
        if ($this->remaining_amount == 0) {
            $this->status = 'lunas';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'cicilan';
        } else {
            $this->status = 'belum_bayar';
        }
        $this->save();
    }
}
