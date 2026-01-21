<?php

namespace App\Observers;

use App\Models\CalonSantri;
use App\Models\Pembayaran;
use App\Models\PembayaranItem;
use App\Models\Sekolah;

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

        // Simpan sekolah jika belum ada
        if ($calonSantri->asal_sekolah) {
            Sekolah::firstOrCreate(
                ['nama' => $calonSantri->asal_sekolah],
                ['jumlah_santri' => 1]
            );
        }
    }

    /**
     * Handle the CalonSantri "updated" event.
     */
    public function updated(CalonSantri $calonSantri): void
    {
        // Update sekolah count jika asal_sekolah berubah
        $oldSekolah = $calonSantri->getOriginal('asal_sekolah');
        $newSekolah = $calonSantri->asal_sekolah;

        if ($oldSekolah !== $newSekolah) {
            // Kurangi count sekolah lama
            if ($oldSekolah) {
                $sekolahLama = Sekolah::where('nama', $oldSekolah)->first();
                if ($sekolahLama) {
                    $sekolahLama->jumlah_santri = max(0, $sekolahLama->jumlah_santri - 1);
                    $sekolahLama->save();
                }
            }

            // Tambah count sekolah baru
            if ($newSekolah) {
                Sekolah::firstOrCreate(
                    ['nama' => $newSekolah],
                    ['jumlah_santri' => 1]
                );
                
                $sekolahBaru = Sekolah::where('nama', $newSekolah)->first();
                if ($sekolahBaru) {
                    $sekolahBaru->increment('jumlah_santri');
                }
            }
        }
    }

    /**
     * Handle the CalonSantri "deleted" event.
     */
    public function deleted(CalonSantri $calonSantri): void
    {
        // Delete related pembayaran
        $calonSantri->pembayaran()?->delete();

        // Kurangi count sekolah
        if ($calonSantri->asal_sekolah) {
            $sekolah = Sekolah::where('nama', $calonSantri->asal_sekolah)->first();
            if ($sekolah) {
                $sekolah->jumlah_santri = max(0, $sekolah->jumlah_santri - 1);
                $sekolah->save();
            }
        }
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
