@extends('layouts.admin')

@section('title', 'Pencatatan Keuangan')
@section('page-title', '💰 Pencatatan Keuangan')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Kelola semua pencatatan pemasukan dan pengeluaran</p>
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="grid grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600 mt-2">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-red-600 mt-2">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600">Saldo Total</p>
            <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-2">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600">💵 Saldo Cash</p>
            <p class="text-xl font-bold {{ $cashBalance >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                Rp {{ number_format($cashBalance, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600">🏦 Saldo Transfer</p>
            <p class="text-xl font-bold {{ $transferBalance >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                Rp {{ number_format($transferBalance, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Filter & Add Button -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">🔍 Filter Data</h3>
            <a href="{{ route('admin.financial-records.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                ➕ Tambah Pencatatan
            </a>
        </div>

        <form method="GET" class="grid grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded">
                    <option value="">Semua</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded">
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>
            <div class="col-span-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold">🔍 Filter</button>
                <a href="{{ route('admin.financial-records.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold ml-2">↻ Reset</a>
            </div>
        </form>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($records->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Jenis</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Kategori</th>
                        <th class="px-6 py-3 text-right text-sm font-bold text-gray-700">Jumlah</th>
                        <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Metode</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Ref/Kwitansi</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Dicatat Oleh</th>
                        <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $record->transaction_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                @if($record->type == 'income')
                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">📈 Pemasukan</span>
                                @else
                                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">📉 Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $record->category }}</td>
                            <td class="px-6 py-4 text-right font-semibold {{ $record->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($record->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($record->payment_method == 'cash')
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">💵 Cash</span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">🏦 Transfer</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-mono">{{ $record->reference_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $record->recorded_by ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.financial-records.edit', $record) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">✏️ Edit</a>
                                    <form action="{{ route('admin.financial-records.destroy', $record) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm" onclick="return confirm('Yakin ingin menghapus pencatatan ini?')">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $records->links() }}
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <p class="mb-2">📭 Belum ada pencatatan keuangan</p>
                <a href="{{ route('admin.financial-records.create') }}" class="text-blue-600 hover:underline font-semibold">Tambah Pencatatan Sekarang</a>
            </div>
        @endif
    </div>
@endsection
