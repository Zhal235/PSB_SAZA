<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use App\Models\FinancialRecord;
use App\Models\Pembayaran;
use App\Models\PembayaranRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Display a listing of pembayaran
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        // Ambil pembayaran terbaru untuk setiap calon santri (avoid duplicate)
        $pembayarans = Pembayaran::with('calonSantri', 'itemDetails')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('calonSantri', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('no_pendaftaran', 'like', '%' . $search . '%')
                      ->orWhere('jenjang', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('calon_santri_id')
            ->map(function($group) {
                return $group->first(); // Ambil yang paling baru
            })
            ->values();
        
        // Hitung total dari itemDetails yang dipilih
        $pembayarans = $pembayarans->map(function($pembayaran) {
            // Gunakan total dari database yang sudah dihitung
            $pembayaran->calculated_total = $pembayaran->total_amount;
            $pembayaran->calculated_remaining = $pembayaran->total_amount - $pembayaran->paid_amount;
            return $pembayaran;
        });
        
        return view('admin.pembayaran.index', compact('pembayarans', 'search'));
    }

    /**
     * Show detail pembayaran santri
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'records', 'itemDetails.pembayaranItem');
        
        // Gunakan total_amount yang sudah tersimpan di database (hasil dari itemDetails)
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

        // Auto-create Financial Record untuk pencatatan keuangan
        FinancialRecord::create([
            'transaction_date' => $validated['paid_at'],
            'type' => 'income',
            'category' => 'Pembayaran Pendaftaran - ' . $pembayaran->calonSantri->nama,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'] == 'check' ? 'transfer' : $validated['payment_method'],
            'reference_number' => $validated['receipt_number'] ?? null,
            'description' => 'Pembayaran dari ' . $pembayaran->calonSantri->nama . ' - ' . ($validated['notes'] ?? ''),
            'recorded_by' => Auth::user()->name,
        ]);

        return back()->with('success', '✅ Pembayaran berhasil dicatat!');
    }

    /**
     * Generate Invoice
     */
    public function invoice(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'records', 'itemDetails.pembayaranItem');
        
        // Ambil items yang dipilih santri dari itemDetails
        $items = $pembayaran->itemDetails->map(function($detail) {
            return $detail->pembayaranItem;
        });
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;

        // Sinkronkan status dengan nilai yang sudah dihitung ulang
        if ($pembayaran->remaining_amount <= 0) {
            $pembayaran->status = 'lunas';
        } elseif ($pembayaran->paid_amount > 0) {
            $pembayaran->status = 'cicilan';
        } else {
            $pembayaran->status = 'belum_bayar';
        }

        $bankSettings = \App\Models\BankSetting::where('is_active', true)->get();
        $kopImagePath = asset('images/KOP.png');
        
        return view('admin.pembayaran.invoice', compact('pembayaran', 'bankSettings', 'kopImagePath', 'items'));
    }

    /**
     * Download Invoice as PDF
     */
    public function invoicePdf(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'records', 'itemDetails.pembayaranItem');

        // Ambil items yang dipilih santri dari itemDetails
        $items = $pembayaran->itemDetails->map(function($detail) {
            return $detail->pembayaranItem;
        });
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;

        if ($pembayaran->remaining_amount <= 0) {
            $pembayaran->status = 'lunas';
        } elseif ($pembayaran->paid_amount > 0) {
            $pembayaran->status = 'cicilan';
        } else {
            $pembayaran->status = 'belum_bayar';
        }

        $bankSettings = \App\Models\BankSetting::where('is_active', true)->get();
        $kopImagePath = 'file://' . public_path('images/KOP.png');
        
        $html = view('admin.pembayaran.invoice', compact('pembayaran', 'bankSettings', 'kopImagePath', 'items'))->render();
        
        // Menggunakan library bawaannya
        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4')
            ->setOrientation('portrait');
        
        return $pdf->download('invoice-' . $pembayaran->calonSantri->nama . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Show form untuk edit items yang dibeli
     */
    public function editItems(Pembayaran $pembayaran)
    {
        $pembayaran->load('calonSantri', 'itemDetails.pembayaranItem');
        
        $activeItems = \App\Models\PembayaranItem::where('status', 'active')->get();
        $selectedItemIds = $pembayaran->itemDetails->pluck('pembayaran_item_id')->toArray();
        
        return view('admin.pembayaran.edit-items', compact('pembayaran', 'activeItems', 'selectedItemIds'));
    }

    /**
     * Update items yang dibeli santri
     */
    public function updateItems(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'items' => 'nullable|array',
            'items.*' => 'integer|exists:pembayaran_items,id',
        ]);

        // Tambahkan item wajib secara otomatis
        $requiredItems = \App\Models\PembayaranItem::where('status', 'active')
            ->where('is_required', true)
            ->pluck('id')
            ->toArray();
        
        // Merge required items dengan selected items
        $selectedItems = $validated['items'] ?? [];
        $allSelectedItems = array_unique(array_merge($selectedItems, $requiredItems));

        // Delete existing item details
        $pembayaran->itemDetails()->delete();

        // Add new item details
        if (!empty($allSelectedItems)) {
            foreach ($allSelectedItems as $itemId) {
                $item = \App\Models\PembayaranItem::find($itemId);
                
                $pembayaran->itemDetails()->create([
                    'pembayaran_item_id' => $itemId,
                    'quantity' => 1,
                    'unit_price' => $item->nominal,
                    'subtotal' => $item->nominal,
                ]);
            }
        }

        // Recalculate total amount
        $totalAmount = $pembayaran->itemDetails->sum('subtotal');
        $pembayaran->update([
            'total_amount' => $totalAmount,
            'remaining_amount' => $totalAmount - $pembayaran->paid_amount,
        ]);

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', '✅ Item berhasil diperbarui!');
    }

    /**
     * Show form to edit payment record
     */
    public function editRecord(PembayaranRecord $record)
    {
        $pembayaran = $record->pembayaran;
        $pembayaran->load('calonSantri');
        return view('admin.pembayaran.edit-record', compact('record', 'pembayaran'));
    }

    /**
     * Update payment record
     */
    public function updateRecord(Request $request, PembayaranRecord $record)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,check',
            'paid_at' => 'required|date',
            'notes' => 'nullable|string',
            'receipt_number' => 'nullable|string',
        ]);

        $validated['paid_at'] = $validated['paid_at'] . ' ' . $record->paid_at->format('H:i:s');

        $pembayaran = $record->pembayaran;

        // Cari FinancialRecord lama sebelum record diupdate
        $oldAmount = $record->amount;
        $oldMethod = $record->payment_method == 'check' ? 'transfer' : $record->payment_method;
        $financialRecord = \App\Models\FinancialRecord::where('type', 'income')
            ->where('amount', $oldAmount)
            ->where('payment_method', $oldMethod)
            ->where(function($q) use ($pembayaran, $record) {
                $q->where('category', 'Pembayaran Pendaftaran - ' . $pembayaran->calonSantri->nama)
                  ->orWhere('reference_number', 'BUKTI-' . $record->id . '-' . $record->unique_code);
            })->first();

        // Revert old amount
        $pembayaran->paid_amount -= $record->amount;
        
        // Update record
        $record->update($validated);

        // Update Financial Record
        if ($financialRecord) {
            $financialRecord->update([
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'] == 'check' ? 'transfer' : $validated['payment_method'],
                'transaction_date' => $validated['paid_at'],
                'reference_number' => $validated['receipt_number'] ?? $financialRecord->reference_number,
                'description' => 'Pembayaran dari ' . $pembayaran->calonSantri->nama . ' - ' . ($validated['notes'] ?? ''),
            ]);
        }

        // Apply new amount
        $pembayaran->paid_amount += $validated['amount'];
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;
        $pembayaran->updateStatus();

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', '✅ Data pembayaran berhasil diubah!');
    }

    /**
     * Delete payment record
     */
    public function destroyRecord(PembayaranRecord $record)
    {
        $pembayaran = $record->pembayaran;

        // Cari dan hapus FinancialRecord terkait
        $oldAmount = $record->amount;
        $oldMethod = $record->payment_method == 'check' ? 'transfer' : $record->payment_method;
        $financialRecord = \App\Models\FinancialRecord::where('type', 'income')
            ->where('amount', $oldAmount)
            ->where('payment_method', $oldMethod)
            ->where(function($q) use ($pembayaran, $record) {
                $q->where('category', 'Pembayaran Pendaftaran - ' . $pembayaran->calonSantri->nama)
                  ->orWhere('reference_number', 'BUKTI-' . $record->id . '-' . $record->unique_code);
            })->first();

        if ($financialRecord) {
            $financialRecord->delete();
        }

        // Revert amount
        $pembayaran->paid_amount -= $record->amount;
        $pembayaran->remaining_amount = $pembayaran->total_amount - $pembayaran->paid_amount;
        $pembayaran->updateStatus();

        $record->delete();

        return redirect()->route('admin.pembayaran.show', $pembayaran)->with('success', '✅ Data pembayaran berhasil dihapus!');
    }
}


