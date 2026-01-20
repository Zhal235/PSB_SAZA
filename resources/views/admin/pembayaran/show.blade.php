@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">{{ $pembayaran->calonSantri->nama }}</p>
@endsection

@section('top-bar-action')
    <div class="flex gap-3">
        <a href="{{ route('admin.pembayaran.invoice', $pembayaran) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 font-semibold" target="_blank">
            📄 Lihat Invoice
        </a>
        <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 font-semibold">
            ← Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Summary Card -->
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Tagihan</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">
                    Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Sudah Dibayar</p>
                <p class="text-2xl font-bold text-green-600 mt-2">
                    Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Sisa Tagihan</p>
                <p class="text-2xl font-bold text-red-600 mt-2">
                    Rp {{ number_format($pembayaran->remaining_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-lg font-bold mt-2">
                    @if($pembayaran->status === 'lunas')
                        <span class="text-green-600">✅ Lunas</span>
                    @elseif($pembayaran->status === 'cicilan')
                        <span class="text-yellow-600">🔄 Cicilan</span>
                    @else
                        <span class="text-red-600">❌ Belum Bayar</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Input Pembayaran -->
        @if($pembayaran->remaining_amount > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">💵 Input Pembayaran</h3>
                
                <form method="POST" action="{{ route('admin.pembayaran.storePayment', $pembayaran) }}" class="space-y-4">
                    @csrf

                    <!-- Jumlah Pembayaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Pembayaran (Rp) *</label>
                        <input type="number" name="amount" placeholder="0" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <p class="text-xs text-gray-600 mt-1">Sisa yang harus dibayar: Rp {{ number_format($pembayaran->remaining_amount, 0, ',', '.') }}</p>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran *</label>
                        <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash">💵 Tunai</option>
                            <option value="transfer">🏦 Transfer Bank</option>
                        </select>
                    </div>

                    <!-- Tanggal Pembayaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pembayaran *</label>
                        <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>

                    <!-- Nomor Kwitansi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kwitansi (opsional)</label>
                        <input type="text" name="receipt_number" placeholder="KWS-2026-001" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" placeholder="Catatan pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                        ✅ Simpan Pembayaran
                    </button>
                </form>
            </div>
        @else
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded font-semibold">
                ✅ Pembayaran Sudah Lunas!
            </div>
        @endif

        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Riwayat Pembayaran</h3>
            
            @if($pembayaran->records->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Metode</th>
                                <th class="px-4 py-2 text-right">Jumlah</th>
                                <th class="px-4 py-2 text-left">Kwitansi</th>
                                <th class="px-4 py-2 text-left">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($pembayaran->records as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold text-gray-700">
                                        {{ $record->paid_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($record->payment_method === 'cash')
                                            💵 Tunai
                                        @elseif($record->payment_method === 'transfer')
                                            🏦 Transfer
                                        @else
                                            📋 Cek
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-green-600">
                                        Rp {{ number_format($record->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">
                                        {{ $record->receipt_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">
                                        {{ $record->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-6">Belum ada riwayat pembayaran</p>
            @endif
        </div>
    </div>
@endsection
