@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')
@section('page-title', '💰 Kelola Pembayaran')
@section('page-subtitle', '<p class="text-sm text-gray-600 mt-1">Tracking pembayaran calon santri</p>')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No. Pendaftaran</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Santri</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total Tagihan</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Sudah Bayar</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Sisa Bayar</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-sm font-semibold text-gray-700">
                            {{ $pembayaran->calonSantri->no_pendaftaran }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $pembayaran->calonSantri->nama }}</div>
                            <p class="text-xs text-gray-600 mt-1">{{ $pembayaran->calonSantri->jenjang }}</p>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-700">
                            Rp {{ number_format($pembayaran->calculated_total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">
                            Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-red-600">
                            Rp {{ number_format($pembayaran->calculated_remaining, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($pembayaran->paid_amount >= $pembayaran->calculated_total)
                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ✅ Lunas
                                </span>
                            @elseif($pembayaran->paid_amount > 0)
                                <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    🔄 Cicilan
                                </span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    ❌ Belum Bayar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                    👁️ Detail
                                </a>
                                <a href="{{ route('admin.pembayaran.invoice', $pembayaran) }}" class="text-purple-600 hover:text-purple-800 font-semibold text-sm" target="_blank">
                                    📄 Invoice
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
