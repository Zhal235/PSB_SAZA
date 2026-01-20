<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialRecord::query();

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $records = $query->orderBy('transaction_date', 'desc')->paginate(20);

        // Calculate totals
        $totalIncome = FinancialRecord::where('type', 'income')->sum('amount');
        $totalExpense = FinancialRecord::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Calculate by payment method
        $cashIncome = FinancialRecord::where('type', 'income')->where('payment_method', 'cash')->sum('amount');
        $cashExpense = FinancialRecord::where('type', 'expense')->where('payment_method', 'cash')->sum('amount');
        $cashBalance = $cashIncome - $cashExpense;

        $transferIncome = FinancialRecord::where('type', 'income')->where('payment_method', 'transfer')->sum('amount');
        $transferExpense = FinancialRecord::where('type', 'expense')->where('payment_method', 'transfer')->sum('amount');
        $transferBalance = $transferIncome - $transferExpense;

        return view('admin.financial-records.index', compact('records', 'totalIncome', 'totalExpense', 'balance', 'cashBalance', 'transferBalance'));
    }

    public function create()
    {
        return view('admin.financial-records.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer',
            'reference_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['recorded_by'] = Auth::user()->name;

        FinancialRecord::create($validated);

        return redirect()->route('admin.financial-records.index')
            ->with('success', 'Pencatatan keuangan berhasil ditambahkan');
    }

    public function edit(FinancialRecord $financialRecord)
    {
        return view('admin.financial-records.edit', compact('financialRecord'));
    }

    public function update(Request $request, FinancialRecord $financialRecord)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer',
            'reference_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $financialRecord->update($validated);

        return redirect()->route('admin.financial-records.index')
            ->with('success', 'Pencatatan keuangan berhasil diupdate');
    }

    public function destroy(FinancialRecord $financialRecord)
    {
        $financialRecord->delete();

        return redirect()->route('admin.financial-records.index')
            ->with('success', 'Pencatatan keuangan berhasil dihapus');
    }
}
