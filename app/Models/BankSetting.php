<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSetting extends Model
{
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'description',
        'phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
