<?php

namespace App\Http\Controllers;

use App\Models\BankSetting;
use Illuminate\Http\Request;

class BankSettingController extends Controller
{
    public function index()
    {
        $banks = BankSetting::orderBy('bank_name')->get();
        return view('admin.bank-settings.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.bank-settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // Handle checkbox - convert "on" string to boolean
        $validated['is_active'] = $request->has('is_active') ? true : false;

        BankSetting::create($validated);

        return redirect()->route('admin.bank-settings.index')
            ->with('success', 'Bank berhasil ditambahkan');
    }

    public function edit(BankSetting $bankSetting)
    {
        return view('admin.bank-settings.edit', compact('bankSetting'));
    }

    public function update(Request $request, BankSetting $bankSetting)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // Handle checkbox - convert "on" string to boolean
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $bankSetting->update($validated);

        return redirect()->route('admin.bank-settings.index')
            ->with('success', 'Bank berhasil diupdate');
    }

    public function destroy(BankSetting $bankSetting)
    {
        $bankSetting->delete();

        return redirect()->route('admin.bank-settings.index')
            ->with('success', 'Bank berhasil dihapus');
    }
}
