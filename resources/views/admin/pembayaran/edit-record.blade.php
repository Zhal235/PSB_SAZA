@extends('layouts.admin')

@section('title', 'Edit Pembayaran')
@section('page-title', 'Edit Pembayaran')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">{{ $pembayaran->calonSantri->nama }}</p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
        <h3 class="text-lg font-bold text-gray-800 mb-4">✏️ Edit Data Pembayaran</h3>
        
        <form method="POST" action="{{ route('admin.pembayaran-record.update', $record) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Jumlah Pembayaran -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Pembayaran (Rp) *</label>
                <input type="number" name="amount" value="{{ $record->amount }}" placeholder="0" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Metode Pembayaran -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran *</label>
                <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="cash" {{ $record->payment_method === 'cash' ? 'selected' : '' }}>💵 Tunai</option>
                    <option value="transfer" {{ $record->payment_method === 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                    <option value="check" {{ $record->payment_method === 'check' ? 'selected' : '' }}>📋 Cek</option>
                </select>
            </div>

            <!-- Tanggal Pembayaran -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pembayaran *</label>
                <input type="date" name="paid_at" value="{{ $record->paid_at->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Nomor Kwitansi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kwitansi (opsional)</label>
                <input type="text" name="receipt_number" value="{{ $record->receipt_number }}" placeholder="KWS-2026-001" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $record->notes }}</textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 font-semibold text-center py-2 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                    ✅ Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
