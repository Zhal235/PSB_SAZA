@extends('layouts.admin')

@section('title', 'Tambah Pencatatan Keuangan')
@section('page-title', '➕ Tambah Pencatatan Keuangan')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Tambah pencatatan pemasukan atau pengeluaran</p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow max-w-2xl">
        <div class="p-8">
            <form action="{{ route('admin.financial-records.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Transaction Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi *</label>
                    <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('transaction_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi *</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>📈 Pemasukan</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>📉 Pengeluaran</option>
                    </select>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                    <input type="text" name="category" placeholder="Contoh: Pendaftaran, Donasi, SPP, Operasional, dll" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('category') }}" required>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
                    <input type="number" name="amount" placeholder="0" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                    <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash (Tunai)</option>
                        <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Referensi / Kwitansi</label>
                    <input type="text" name="reference_number" placeholder="Contoh: KWS-2026-001" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('reference_number') }}">
                    @error('reference_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi / Catatan</label>
                    <textarea name="description" placeholder="Catatan tambahan tentang transaksi ini" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                        ✅ Simpan
                    </button>
                    <a href="{{ route('admin.financial-records.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold">
                        ❌ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
