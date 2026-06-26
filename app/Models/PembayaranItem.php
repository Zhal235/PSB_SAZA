<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranItem extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'nominal',
        'nominal_old',
        'effective_date',
        'is_required',
        'can_cicil',
        'cicil_month',
        'status',
        'item_type',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'nominal_old' => 'decimal:2',
        'effective_date' => 'date',
        'is_required' => 'boolean',
        'can_cicil' => 'boolean',
    ];

    public function itemDetails()
    {
        return $this->hasMany(PembayaranItemDetail::class);
    }

    /**
     * Get price applicable for a given date
     * If the date is before effective_date, use nominal_old (if exists), otherwise use nominal
     */
    public function getPriceForDate($dateToCheck)
    {
        // Convert to carbon date if string
        if (is_string($dateToCheck)) {
            $dateToCheck = \Carbon\Carbon::parse($dateToCheck);
        }

        // If there's an effective_date and the check date is before it
        if ($this->effective_date && $dateToCheck < $this->effective_date) {
            // Use nominal_old if it exists, otherwise use nominal
            return $this->nominal_old ?? $this->nominal;
        }

        // Use nominal for dates on or after effective_date
        return $this->nominal;
    }
}
