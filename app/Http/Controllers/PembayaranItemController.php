<?php

namespace App\Http\Controllers;

use App\Models\PembayaranItem;
use Illuminate\Http\Request;

class PembayaranItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = PembayaranItem::all();
        return view('admin.pembayaran.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pembayaran.items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'is_required' => 'boolean',
            'can_cicil' => 'boolean',
            'cicil_month' => 'nullable|numeric|min:1|max:12',
        ]);

        PembayaranItem::create($validated);

        return redirect()->route('admin.pembayaran-items.index')->with('success', '✅ Item pembayaran berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PembayaranItem $pembayaranItem)
    {
        return view('admin.pembayaran.items.edit', compact('pembayaranItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembayaranItem $pembayaranItem)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'is_required' => 'boolean',
            'can_cicil' => 'boolean',
            'cicil_month' => 'nullable|numeric|min:1|max:12',
            'status' => 'required|in:active,inactive',
        ]);

        $pembayaranItem->update($validated);

        return redirect()->route('admin.pembayaran-items.index')->with('success', '✅ Item pembayaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembayaranItem $pembayaranItem)
    {
        $pembayaranItem->delete();
        return back()->with('success', '✅ Item pembayaran berhasil dihapus!');
    }
}
