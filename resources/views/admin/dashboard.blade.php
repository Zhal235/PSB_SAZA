<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar - BSI Green -->
        <div class="w-64 bg-[#00A651] text-white p-6 flex flex-col shadow-lg">
            <div class="mb-8 border-b border-white/20 pb-4">
                <h1 class="text-3xl font-bold">PSB SAZA</h1>
                <p class="text-[#E8F5E9] text-sm mt-1">Sistem Pendaftaran Santri</p>
            </div>

            <nav class="space-y-2 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded bg-[#003F87] hover:bg-[#003F87] transition font-semibold">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-2 rounded hover:bg-[#003F87] transition">
                    👥 Kelola Pendaftar
                </a>
                <a href="{{ route('verifikasi-dokumen.index') }}" class="block px-4 py-2 rounded hover:bg-[#003F87] transition">
                    📋 Verifikasi Dokumen
                </a>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-2 rounded hover:bg-[#003F87] transition">
                    💳 Item Pembayaran
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-2 rounded hover:bg-[#003F87] transition">
                    💰 Kelola Pembayaran
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-[#003F87] transition">
                    📊 Laporan
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-[#003F87] transition">
                    ⚙️ Pengaturan
                </a>
            </nav>

            <hr class="my-6 border-white/20">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full px-4 py-2 rounded bg-[#FF9900] hover:bg-[#E68A00] transition font-semibold text-sm text-white"
                >
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white shadow-md p-6 flex justify-between items-center border-b-4 border-[#00A651]">
                <h2 class="text-2xl font-bold text-[#003F87]">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <div class="text-right">
                    <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                    <p class="text-[#00A651] font-semibold text-sm">Admin</p>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-[#00A651]">
                            <h3 class="text-gray-600 text-sm font-medium mb-2">Total Pendaftar</h3>
                            <p class="text-3xl font-bold text-[#00A651]">--</p>
                            <p class="text-gray-500 text-xs mt-2">Bulan ini</p>
                        </div>

                        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-[#FF9900]">
                            <h3 class="text-gray-600 text-sm font-medium mb-2">Menunggu Verifikasi</h3>
                            <p class="text-3xl font-bold text-[#FF9900]">--</p>
                            <p class="text-gray-500 text-xs mt-2">Dokumen</p>
                        </div>

                        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-[#00A651]">
                            <h3 class="text-gray-600 text-sm font-medium mb-2">Lolos Seleksi</h3>
                            <p class="text-3xl font-bold text-[#00A651]">--</p>
                            <p class="text-gray-500 text-xs mt-2">Terkonfirmasi</p>
                        </div>

                        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-red-500">
                            <h3 class="text-gray-600 text-sm font-medium mb-2">Tidak Lolos</h3>
                            <p class="text-3xl font-bold text-red-600">--</p>
                            <p class="text-gray-500 text-xs mt-2">Ditolak</p>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#00A651]">
                        <h3 class="text-lg font-bold text-[#003F87] mb-4">Aktivitas Terbaru</h3>
                        <p class="text-gray-500 text-center py-8">Tidak ada aktivitas terbaru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
