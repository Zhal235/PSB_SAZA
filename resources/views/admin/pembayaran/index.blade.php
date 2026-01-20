<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pembayaran - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-indigo-600 text-white p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">PSB SAZA</h1>
                <p class="text-indigo-200 text-sm">Admin Panel</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    👥 Kelola Pendaftar
                </a>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    💳 Item Pembayaran
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-2 rounded bg-indigo-700 transition font-semibold">
                    💰 Kelola Pembayaran
                </a>
            </nav>

            <hr class="my-6 border-indigo-400">

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="w-full px-4 py-2 rounded bg-red-500 hover:bg-red-600 transition font-semibold text-sm">
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow p-6 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">💰 Kelola Pembayaran</h2>
                    <p class="text-sm text-gray-600 mt-1">Tracking pembayaran calon santri</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        {{ session('success') }}
                    </div>
                @endif

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
                                        Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-green-600">
                                        Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-red-600">
                                        Rp {{ number_format($pembayaran->remaining_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($pembayaran->status === 'lunas')
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                ✅ Lunas
                                            </span>
                                        @elseif($pembayaran->status === 'cicilan')
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

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $pembayarans->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
