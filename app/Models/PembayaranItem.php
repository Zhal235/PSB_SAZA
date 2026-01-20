<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranItem extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'nominal',
        'is_required',
        'can_cicil',
        'cicil_month',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'is_required' => 'boolean',
        'can_cicil' => 'boolean',
    ];

    public function pembayaranDetails()
    {
        return $this->hasMany(PembayaranDetail::class);
    }
}
