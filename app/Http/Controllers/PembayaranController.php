<?php

namespace App\Http\Controllers;

use App\Exports\PembayaranExport;
use App\Models\CalonSantri;
use App\Models\FinancialRecord;
use App\Models\Pembayaran;
use App\Models\PembayaranRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PembayaranController extends Controller
{
    /**
     * Display a listing of pembayaran
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'nama' => trim((string) $request->query('nama', '')),
            'no_pendaftaran' => trim((string) $request->query('no_pendaftaran', '')),
            'jenjang' => trim((string) $request->query('jenjang', '')),
            'status' => trim((string) $request->query('status', '')),
            'due_date_from' => trim((string) $request->query('due_date_from', '')),
            'due_date_to' => trim((string) $request->query('due_date_to', '')),
            'min_total' => trim((string) $request->query('min_total', '')),
            'max_total' => trim((string) $request->query('max_total', '')),
            'min_paid' => trim((string) $request->query('min_paid', '')),
            'max_paid' => trim((string) $request->query('max_paid', '')),
            'min_remaining' => trim((string) $request->query('min_remaining', '')),
            'max_remaining' => trim((string) $request->query('max_remaining', '')),
        ];

        $query = Pembayaran::with('calonSantri', 'itemDetails');

        if ($filters['search'] !== '') {
            $query->whereHas('calonSantri', function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('no_pendaftaran', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('jenjang', 'like', '%' . $filters['search'] . '%');
            });
        }

        if ($filters['nama'] !== '') {
            $query->whereHas('calonSantri', function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['nama'] . '%');
            });
        }

        if ($filters['no_pendaftaran'] !== '') {
            $query->whereHas('calonSantri', function ($q) use ($filters) {
                $q->where('no_pendaftaran', 'like', '%' . $filters['no_pendaftaran'] . '%');
            });
        }

        if ($filters['jenjang'] !== '') {
            $query->whereHas('calonSantri', function ($q) use ($filters) {
                $q->where('jenjang', 'like', '%' . $filters['jenjang'] . '%');
            });
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['due_date_from'] !== '') {
            $query->whereDate('due_date', '>=', $filters['due_date_from']);
        }

        if ($filters['due_date_to'] !== '') {
            $query->whereDate('due_date', '<=', $filters['due_date_to']);
        }

        if ($filters['min_total'] !== '' && is_numeric($filters['min_total'])) {
            $query->where('total_amount', '>=', (float) $filters['min_total']);
        }

        if ($filters['max_total'] !== '' && is_numeric($filters['max_total'])) {
            $query->where('total_amount', '<=', (float) $filters['max_total']);
        }

        if ($filters['min_paid'] !== '' && is_numeric($filters['min_paid'])) {
            $query->where('paid_amount', '>=', (float) $filters['min_paid']);
        }

        if ($filters['max_paid'] !== '' && is_numeric($filters['max_paid'])) {
            $query->where('paid_amount', '<=', (float) $filters['max_paid']);
        }

        if ($filters['min_remaining'] !== '' && is_numeric($filters['min_remaining'])) {
            $query->where('remaining_amount', '>=', (float) $filters['min_remaining']);
        }

        if ($filters['max_remaining'] !== '' && is_numeric($filters['max_remaining'])) {
            $query->where('remaining_amount', '<=', (float) $filters['max_remaining']);
        }

        $pembayarans = $query
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('calon_santri_id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values()
            ->map(function ($pembayaran) {
                $pembayaran->calculated_total = $pembayaran->total_amount;
                $pembayaran->calculated_remaining = $pembayaran->total_amount - $pembayaran->paid_amount;

                return $pembayaran;
            });

        return view('admin.pembayaran.index', compact('pembayarans', 'filters'));
    }

    /**
     * Export pembayaran ke Excel
     */
    public function export(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'nama' => trim((string) $request->query('nama', '')),
            'no_pendaftaran' => trim((string) $request->query('no_pendaftaran', '')),
            'jenjang' => trim((string) $request->query('jenjang', '')),
            'status' => trim((string) $request->query('status', '')),
            'due_date_from' => trim((string) $request->query('due_date_from', '')),
            'due_date_to' => trim((string) $request->query('due_date_to', '')),
            'min_total' => trim((string) $request->query('min_total', '')),
            'max_total' => trim((string) $request->query('max_total', '')),
            'min_paid' => trim((string) $request->query('min_paid', '')),
            'max_paid' => trim((string) $request->query('max_paid', '')),
            'min_remaining' => trim((string) $request->query('min_remaining', '')),
            'max_remaining' => trim((string) $request->query('max_remaining', '')),
        ];

        $fileName = 'Pembayaran_' . now()->format('d-m-Y-H-i-s') . '.xlsx';

        return Excel::download(new PembayaranExport($filters), $fileName);
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


