<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Item Pembayaran - PSB SAZA</title>
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
                <a href="{{ route('verifikasi-dokumen.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📋 Verifikasi Dokumen
                </a>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-2 rounded bg-indigo-700 transition font-semibold">
                    💳 Item Pembayaran
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
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
                    <h2 class="text-2xl font-bold text-gray-800">💳 Manage Item Pembayaran</h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola item/komponen pembayaran</p>
                </div>
                <a href="{{ route('admin.pembayaran-items.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold">
                    ➕ Tambah Item
                </a>
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
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Item</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nominal</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Wajib</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Cicil</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800">{{ $item->nama }}</div>
                                        @if($item->deskripsi)
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($item->deskripsi, 50) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-indigo-600">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->is_required)
                                            <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                ✓ Wajib
                                            </span>
                                        @else
                                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                ◯ Optional
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->can_cicil)
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                ✓ {{ $item->cicil_month }} bln
                                            </span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs">
                                                Tidak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->status === 'active')
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">
                                                🟢 Aktif
                                            </span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded text-xs font-semibold">
                                                ⚪ Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <a href="{{ route('admin.pembayaran-items.edit', $item) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                                ✏️ Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.pembayaran-items.destroy', $item) }}" class="inline" onsubmit="return confirm('Yakin hapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada item pembayaran. <a href="{{ route('admin.pembayaran-items.create') }}" class="text-indigo-600 hover:underline font-semibold">Tambah sekarang</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
