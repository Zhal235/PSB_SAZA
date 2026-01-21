<?php

namespace App\Http\Controllers;

use App\Models\PembayaranRecord;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;

class BuktiPembayaranController extends Controller
{
    /**
     * Lihat bukti pembayaran pending
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $bukti = PembayaranRecord::where('payment_method', 'transfer')
            ->where('proof_status', $status)
            ->with(['pembayaran.calonSantri'])
            ->latest()
            ->paginate(15);

        return view('admin.bukti-pembayaran.index', compact('bukti', 'status'));
    }

    /**
     * Lihat detail bukti pembayaran
     */
    public function show(PembayaranRecord $bukti)
    {
        if ($bukti->payment_method !== 'transfer') {
            abort(404);
        }

        return view('admin.bukti-pembayaran.show', compact('bukti'));
    }

    /**
     * Verifikasi bukti pembayaran
     */
    public function verify(Request $request, PembayaranRecord $bukti)
    {
        if ($bukti->payment_method !== 'transfer') {
            abort(404);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['action'] === 'approve') {
            $bukti->update([
                'proof_status' => 'verified',
                'proof_notes' => $validated['notes'] ?? null,
            ]);
            
            // Otomatis buat FinancialRecord (pemasukan) untuk transfer
            FinancialRecord::create([
                'type' => 'income',
                'category' => 'transfer',
                'amount' => $bukti->amount,
                'description' => 'Transfer pembayaran dari ' . $bukti->pembayaran->calonSantri->nama,
                'transaction_date' => now()->toDateString(),
                'reference_number' => 'BUKTI-' . $bukti->id . '-' . $bukti->unique_code,
                'payment_method' => 'transfer',
                'recorded_by' => auth()->user()->name ?? 'Admin',
            ]);
            
            // Update pembayaran status
            $pembayaran = $bukti->pembayaran;
            $pembayaran->updateStatus();

            return redirect()->route('admin.bukti-pembayaran.index')
                ->with('success', '✅ Bukti pembayaran berhasil diverifikasi! Pemasukan transfer otomatis tercatat.');
        } else {
            $bukti->update([
                'proof_status' => 'rejected',
                'proof_notes' => $validated['notes'] ?? 'Ditolak oleh admin',
            ]);

            return redirect()->route('admin.bukti-pembayaran.index')
                ->with('success', '❌ Bukti pembayaran ditolak. Santri diminta mengunggah kembali.');
        }
    }
}
