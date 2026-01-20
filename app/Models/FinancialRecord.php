<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    protected $fillable = [
        'transaction_date',
        'type',
        'category',
        'amount',
        'payment_method',
        'reference_number',
        'description',
        'recorded_by'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2'
    ];
}
