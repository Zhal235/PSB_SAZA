<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use App\Models\Pembayaran;
use App\Models\PembayaranRecord;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Display a listing of pembayaran
     */
    public function index()
    {
        // Ambil pembayaran terbaru untuk setiap calon santri (avoid duplicate)
        $pembayarans = Pembayaran::with('calonSantri')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('calon_santri_id')
            ->map(function($group) {
                return $group->first(); // Ambil yang paling baru
            })
            ->values();
        
        // Hitung total dari active items untuk setiap pembayaran
        $pembayarans = $pembayarans->map(function($pembayaran) {
            $items = \App\Models\PembayaranItem::where('status', 'active')->get();
            $totalFromItems = $items->sum('nominal');
            
            // Gunakan total dari items jika ada, jika tidak gunakan dari db
            $pembayaran->calculated_total = $totalFromItems ?: $pembayaran->total_amount;
            $pembayaran->calculated_remaining = $pembayaran->calculated_total - $pembayaran->paid_amount;
            
            return $pembayaran;
        });
        
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    /**
     * Show detail pembayaran santri
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'records');
        
        // Hitung total dari active items
        $items = \App\Models\PembayaranItem::where('status', 'active')->get();
        $totalFromItems = $items->sum('nominal');
        
        // Gunakan total dari items jika ada
        $pembayaran->total_amount = $totalFromItems ?: $pembayaran->total_amount;
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Store pembayaran record (input pembayaran)
     */
    public function storePayment(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,check',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string',
            'receipt_number' => 'nullable|string',
        ]);

        $validated['pembayaran_id'] = $pembayaran->id;
        $validated['paid_at'] = $validated['paid_at'] . ' ' . now()->format('H:i:s');

        // Create payment record
        PembayaranRecord::create($validated);

        // Update pembayaran
        $pembayaran->paid_amount += $validated['amount'];
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;
        $pembayaran->updateStatus();

        return back()->with('success', '✅ Pembayaran berhasil dicatat!');
    }

    /**
     * Generate Invoice
     */
    public function invoice(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'records');
        
        // Hitung total dari active items
        $items = \App\Models\PembayaranItem::where('status', 'active')->get();
        $totalFromItems = $items->sum('nominal');
        
        // Gunakan total dari items jika ada
        $pembayaran->total_amount = $totalFromItems ?: $pembayaran->total_amount;
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;
        
        return view('admin.pembayaran.invoice', compact('pembayaran'));
    }

    /**
     * Download Invoice as PDF
     */
    public function invoicePdf(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'records');
        
        $html = view('admin.pembayaran.invoice', compact('pembayaran'))->render();
        
        // Menggunakan library bawaannya
        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4')
            ->setOrientation('portrait');
        
        return $pdf->download('invoice-' . $pembayaran->calonSantri->nama . '-' . date('Y-m-d') . '.pdf');
    }
}
