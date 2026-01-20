<?php

namespace App\Observers;

use App\Models\CalonSantri;
use App\Models\Pembayaran;
use App\Models\PembayaranItem;

class CalonSantriObserver
{
    /**
     * Handle the CalonSantri "created" event.
     */
    public function created(CalonSantri $calonSantri): void
    {
        // Auto create pembayaran record
        // Calculate total dari item required
        $totalAmount = PembayaranItem::where('status', 'active')
            ->where('is_required', true)
            ->sum('nominal');

        Pembayaran::create([
            'calon_santri_id' => $calonSantri->id,
            'status' => 'belum_bayar',
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'remaining_amount' => $totalAmount,
            'due_date' => now()->addDays(14),
        ]);
    }

    /**
     * Handle the CalonSantri "updated" event.
     */
    public function updated(CalonSantri $calonSantri): void
    {
        //
    }

    /**
     * Handle the CalonSantri "deleted" event.
     */
    public function deleted(CalonSantri $calonSantri): void
    {
        // Delete related pembayaran
        $calonSantri->pembayaran()?->delete();
    }

    /**
     * Handle the CalonSantri "restored" event.
     */
    public function restored(CalonSantri $calonSantri): void
    {
        //
    }

    /**
     * Handle the CalonSantri "force deleted" event.
     */
    public function forceDeleted(CalonSantri $calonSantri): void
    {
        //
    }
}
