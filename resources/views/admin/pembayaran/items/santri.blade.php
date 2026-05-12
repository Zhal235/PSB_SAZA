@extends('layouts.admin')

@section('title', 'Santri - ' . $pembayaranItem->nama)
@section('page-title', 'Santri yang Membeli: ' . $pembayaranItem->nama)

@section('top-bar-action')
    <a href="{{ route('admin.pembayaran-items.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">← Kembali</a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-indigo-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm font-semibold">Kategori</p>
                <p class="text-xl font-bold text-indigo-600">
                    @if($pembayaranItem->item_type === 'perlengkapan')
                        Perlengkapan
                    @else
                        Pembayaran
                    @endif
                </p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm font-semibold">Nominal</p>
                <p class="text-xl font-bold text-green-600">
                    Rp {{ number_format($pembayaranItem->nominal, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm font-semibold">Total Santri</p>
                <p class="text-xl font-bold text-blue-600">{{ count($santris) }}</p>
            </div>
            <div class="bg-amber-50 p-4 rounded-lg">
                <p class="text-gray-600 text-sm font-semibold">Total Nilai</p>
                <p class="text-xl font-bold text-amber-600">
                    Rp {{ number_format($santris->sum('subtotal'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Santri</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">NIK</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No. HP</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Qty</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Harga Satuan</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Subtotal</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Status Pembayaran</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($santris as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">
                                <a href="{{ route('admin.calon-santri.show', $item['santri']) }}" class="text-indigo-600 hover:underline">
                                    {{ $item['santri']->nama }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item['santri']->nik_santri ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $item['santri']->no_telp ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded font-semibold">
                                {{ $item['quantity'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="font-semibold text-gray-700">
                                Rp {{ number_format($item['unit_price'], 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="font-semibold text-indigo-600">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item['pembayaran']->status === 'lunas')
                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Lunas
                                </span>
                            @elseif($item['pembayaran']->status === 'cicilan')
                                <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Cicilan
                                </span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Belum Bayar
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Belum ada santri yang membeli item ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
