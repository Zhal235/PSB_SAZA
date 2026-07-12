@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Pembayaran')

@section('content')
    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
                <div>
                    <label for="search" class="block text-xs font-semibold text-gray-600 mb-1">Cari umum</label>
                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Nama / no pendaftaran / jenjang"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="nama" class="block text-xs font-semibold text-gray-600 mb-1">Nama santri</label>
                    <input
                        id="nama"
                        type="text"
                        name="nama"
                        value="{{ $filters['nama'] ?? '' }}"
                        placeholder="Ketik nama"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="no_pendaftaran" class="block text-xs font-semibold text-gray-600 mb-1">No. pendaftaran</label>
                    <input
                        id="no_pendaftaran"
                        type="text"
                        name="no_pendaftaran"
                        value="{{ $filters['no_pendaftaran'] ?? '' }}"
                        placeholder="Contoh: PSB-2026"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="jenjang" class="block text-xs font-semibold text-gray-600 mb-1">Jenjang</label>
                    <input
                        id="jenjang"
                        type="text"
                        name="jenjang"
                        value="{{ $filters['jenjang'] ?? '' }}"
                        placeholder="SD / SMP / SMA"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-600 mb-1">Status pembayaran</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua status</option>
                        <option value="belum_bayar" {{ ($filters['status'] ?? '') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="cicilan" {{ ($filters['status'] ?? '') === 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                        <option value="lunas" {{ ($filters['status'] ?? '') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div>
                    <label for="due_date_from" class="block text-xs font-semibold text-gray-600 mb-1">Jatuh tempo dari</label>
                    <input
                        id="due_date_from"
                        type="date"
                        name="due_date_from"
                        value="{{ $filters['due_date_from'] ?? '' }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="due_date_to" class="block text-xs font-semibold text-gray-600 mb-1">Sampai</label>
                    <input
                        id="due_date_to"
                        type="date"
                        name="due_date_to"
                        value="{{ $filters['due_date_to'] ?? '' }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="min_total" class="block text-xs font-semibold text-gray-600 mb-1">Min total tagihan</label>
                    <input
                        id="min_total"
                        type="number"
                        name="min_total"
                        min="0"
                        value="{{ $filters['min_total'] ?? '' }}"
                        placeholder="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="max_total" class="block text-xs font-semibold text-gray-600 mb-1">Max total tagihan</label>
                    <input
                        id="max_total"
                        type="number"
                        name="max_total"
                        min="0"
                        value="{{ $filters['max_total'] ?? '' }}"
                        placeholder="5000000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="min_paid" class="block text-xs font-semibold text-gray-600 mb-1">Min bayar</label>
                    <input
                        id="min_paid"
                        type="number"
                        name="min_paid"
                        min="0"
                        value="{{ $filters['min_paid'] ?? '' }}"
                        placeholder="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="max_paid" class="block text-xs font-semibold text-gray-600 mb-1">Max bayar</label>
                    <input
                        id="max_paid"
                        type="number"
                        name="max_paid"
                        min="0"
                        value="{{ $filters['max_paid'] ?? '' }}"
                        placeholder="2000000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="min_remaining" class="block text-xs font-semibold text-gray-600 mb-1">Min sisa bayar</label>
                    <input
                        id="min_remaining"
                        type="number"
                        name="min_remaining"
                        min="0"
                        value="{{ $filters['min_remaining'] ?? '' }}"
                        placeholder="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label for="max_remaining" class="block text-xs font-semibold text-gray-600 mb-1">Max sisa bayar</label>
                    <input
                        id="max_remaining"
                        type="number"
                        name="max_remaining"
                        min="0"
                        value="{{ $filters['max_remaining'] ?? '' }}"
                        placeholder="5000000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                    Terapkan Filter
                </button>
                @if(collect($filters)->contains(fn ($value) => is_string($value) && trim($value) !== ''))
                    <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg">
                        Reset
                    </a>
                @endif
            </div>
        </form>
        @if(collect($filters)->contains(fn ($value) => is_string($value) && trim($value) !== ''))
            <p class="text-xs text-gray-500 mt-3">
                Menampilkan hasil yang difilter dari parameter pencarian aktif.
            </p>
        @endif
    </div>

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
