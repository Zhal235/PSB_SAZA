<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar-admin')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar - Premium -->
            <div class="bg-white shadow-xl p-6 flex justify-between items-center" style="border-bottom: 4px solid #00a0a0;">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #00a0a0;">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola Sistem Pendaftaran Santri dengan mudah</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                    <p class="font-bold text-sm mt-1 px-3 py-1 rounded-full inline-block" style="background-color: #E8F5E9; color: #00a0a0;">👑 Admin</p>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Statistik Pendaftar by Jenjang -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">👥 Statistik Pendaftar</h3>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="card-bsi bg-gradient-to-br from-white to-blue-50 p-6 border-l-4 border-blue-600 group cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-gray-600 text-sm font-medium mb-2">Total Pendaftar</h3>
                                        <p class="text-4xl font-bold text-blue-600 group-hover:scale-110 transition-transform duration-300">{{ $totalPendaftar }}</p>
                                        <p class="text-gray-500 text-xs mt-2">Semua jenjang</p>
                                    </div>
                                    <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">👥</div>
                                </div>
                            </div>

                            <div class="card-bsi bg-gradient-to-br from-white to-green-50 p-6 border-l-4 border-green-600 group cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-gray-600 text-sm font-medium mb-2">Pendaftar MTs</h3>
                                        <p class="text-4xl font-bold text-green-600 group-hover:scale-110 transition-transform duration-300">{{ $pendaftarMTs }}</p>
                                        <p class="text-gray-500 text-xs mt-2">Madrasah Tsanawiyah</p>
                                    </div>
                                    <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">🎓</div>
                                </div>
                            </div>

                            <div class="card-bsi bg-gradient-to-br from-white to-purple-50 p-6 border-l-4 border-purple-600 group cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-gray-600 text-sm font-medium mb-2">Pendaftar SMK</h3>
                                        <p class="text-4xl font-bold text-purple-600 group-hover:scale-110 transition-transform duration-300">{{ $pendaftarSMK }}</p>
                                        <p class="text-gray-500 text-xs mt-2">Sekolah Menengah Kejuruan</p>
                                    </div>
                                    <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">🎯</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Keuangan -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Statistik Keuangan</h3>
                        <div class="grid grid-cols-5 gap-4">
                            <div class="card-bsi bg-gradient-to-br from-white to-green-50 p-6 border-l-4 border-green-600">
                                <h3 class="text-gray-600 text-xs font-medium mb-2">Pemasukan</h3>
                                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                            </div>
                            <div class="card-bsi bg-gradient-to-br from-white to-red-50 p-6 border-l-4 border-red-600">
                                <h3 class="text-gray-600 text-xs font-medium mb-2">Pengeluaran</h3>
                                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                            </div>
                            <div class="card-bsi bg-gradient-to-br from-white to-blue-50 p-6 border-l-4 border-blue-600">
                                <h3 class="text-gray-600 text-xs font-medium mb-2">Saldo Total</h3>
                                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldoTotal, 0, ',', '.') }}</p>
                            </div>
                            <div class="card-bsi bg-gradient-to-br from-white to-yellow-50 p-6 border-l-4 border-yellow-600">
                                <h3 class="text-gray-600 text-xs font-medium mb-2">💵 Saldo Cash</h3>
                                <p class="text-xl font-bold text-yellow-600">Rp {{ number_format($saldoCash, 0, ',', '.') }}</p>
                            </div>
                            <div class="card-bsi bg-gradient-to-br from-white to-indigo-50 p-6 border-l-4 border-indigo-600">
                                <h3 class="text-gray-600 text-xs font-medium mb-2">🏦 Saldo Transfer</h3>
                                <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($saldoTransfer, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Pembayaran & Dokumen -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <!-- Pembayaran -->
                        <div class="card-bsi bg-white p-6 border-l-4 border-purple-600">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">💳 Status Pembayaran</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span class="text-gray-700">Lunas</span>
                                    <span class="font-bold text-green-600">{{ $pembayaranLunas }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span class="text-gray-700">Cicilan</span>
                                    <span class="font-bold text-yellow-600">{{ $pembayaranCicilan }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span class="text-gray-700">Belum Bayar</span>
                                    <span class="font-bold text-red-600">{{ $pembayaranBelum }}</span>
                                </div>
                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-semibold">Total Tagihan</span>
                                        <span class="font-bold text-blue-600">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-gray-700 font-semibold">Sudah Terbayar</span>
                                        <span class="font-bold text-green-600">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-gray-700 font-semibold">Sisa Tagihan</span>
                                        <span class="font-bold text-red-600">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen -->
                        <div class="card-bsi bg-white p-6 border-l-4 border-orange-600">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">📄 Status Dokumen</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span class="text-gray-700">Total Dokumen</span>
                                    <span class="font-bold text-blue-600">{{ $totalDokumen }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span class="text-gray-700">Sudah Verifikasi</span>
                                    <span class="font-bold text-green-600">{{ $dokumenSudahVerifikasi }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <span class="text-gray-700">Belum Verifikasi</span>
                                    <span class="font-bold text-yellow-600">{{ $dokumenBelumVerifikasi }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card-bsi bg-white p-6 border-l-4 border-[#00A651]">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Pendaftar Terbaru</h3>
                        @if($recentActivities->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100 border-b">
                                        <tr>
                                            <th class="px-4 py-2 text-left">No. Pendaftaran</th>
                                            <th class="px-4 py-2 text-left">Nama</th>
                                            <th class="px-4 py-2 text-center">Jenjang</th>
                                            <th class="px-4 py-2 text-center">Status</th>
                                            <th class="px-4 py-2 text-left">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($recentActivities as $activity)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 font-mono text-xs">{{ $activity->no_pendaftaran }}</td>
                                                <td class="px-4 py-3 font-semibold">{{ $activity->nama }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $activity->jenjang == 'MTs' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700' }}">
                                                        {{ $activity->jenjang }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($activity->status == 'baru')
                                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">Baru</span>
                                                    @elseif($activity->status == 'diterima')
                                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">Diterima</span>
                                                    @else
                                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-gray-600">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-12">Belum ada pendaftar</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
