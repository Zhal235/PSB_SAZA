@extends('layouts.santri')

@section('title', 'Pembayaran')
@section('page-title', '💳 Pembayaran & Tagihan')
@section('page-subtitle', '<p class="text-sm text-gray-600 mt-1">Kelola pembayaran pendaftaran Anda</p>')

@section('content')
    @if($pembayaran)
        @php
            $totalTagihan = $items ? $items->sum('nominal') : $pembayaran->total_amount;
        @endphp
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Tagihan</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
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
                    Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-lg font-bold mt-2">
                    @if($pembayaran->paid_amount >= $totalTagihan)
                        <span class="text-green-600">✅ Lunas</span>
                    @elseif($pembayaran->paid_amount > 0)
                        <span class="text-yellow-600">🔄 Cicilan</span>
                    @else
                        <span class="text-red-600">❌ Belum Bayar</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Breakdown Tagihan -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Rincian Tagihan</h3>
            
            @if($items && $items->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Item</th>
                                <th class="px-4 py-3 text-center">Wajib</th>
                                <th class="px-4 py-3 text-center">Cicil</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800">{{ $item->nama }}</div>
                                        @if($item->deskripsi)
                                            <p class="text-xs text-gray-600 mt-1">{{ $item->deskripsi }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item->is_required)
                                            <span class="text-red-600 font-bold">✓ Wajib</span>
                                        @else
                                            <span class="text-blue-600 text-xs">Optional</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item->can_cicil)
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">{{ $item->cicil_month }} bulan</span>
                                        @else
                                            <span class="text-xs text-gray-500">Tidak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-indigo-600">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="3" class="px-4 py-3 text-right">Total:</td>
                                <td class="px-4 py-3 text-right text-indigo-600 text-lg">
                                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Payment Status Summary -->
                <div class="mt-6 space-y-4">
                    <div class="border border-gray-200 rounded p-4">
                        <p class="text-sm text-gray-600 mb-3">Status Pembayaran Anda</p>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-700 font-semibold">Total Tagihan:</span>
                                <span class="font-semibold text-indigo-600">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-700">Sudah Dibayar:</span>
                                <span class="font-semibold text-green-600">Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-700 font-semibold">Sisa Tagihan:</span>
                                <span class="font-semibold text-red-600 text-lg">Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($pembayaran->due_date)
                                <div class="flex justify-between items-center text-sm pt-3 border-t">
                                    <span class="text-gray-600">Batas Waktu Pembayaran:</span>
                                    <span class="text-gray-700 font-semibold">{{ $pembayaran->due_date->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Opsi Pembayaran -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-sm text-blue-700 font-semibold mb-2">
                            💡 Opsi Pembayaran:
                        </p>
                        <ul class="text-sm text-blue-700 ml-4 space-y-1 list-disc">
                            <li>Pembayaran tunai atau transfer ke rekening sekolah</li>
                            <li>Bisa dicicil sesuai kesepakatan dengan panitia</li>
                            <li>Hubungi panitia untuk info rekening dan metode pembayaran lainnya</li>
                        </ul>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-gray-500">
                    <p>Belum ada item pembayaran yang aktif</p>
                </div>
            @endif
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Riwayat Pembayaran</h3>
            
            @if($pembayaran->records->count() > 0)
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
            @else
                <p class="text-gray-500 text-center py-6">Belum ada riwayat pembayaran</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex gap-3">
            <a href="{{ route('santri.pembayaran-invoice', $pembayaran) }}" 
                class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 font-semibold" target="_blank">
                📄 Lihat Invoice
            </a>
            <a href="{{ route('santri.dashboard') }}" 
                class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold">
                ← Kembali
            </a>
        </div>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded">
            <p class="font-semibold">⚠️ Belum Ada Data Pembayaran</p>
            <p class="text-sm mt-2">Silakan lengkapi form pendaftaran terlebih dahulu untuk melihat detail pembayaran Anda.</p>
            <a href="{{ route('santri.form-pendaftaran') }}" class="text-yellow-900 hover:underline font-semibold mt-3 inline-block">
                → Lengkapi Form Pendaftaran
            </a>
        </div>
    @endif
@endsection
