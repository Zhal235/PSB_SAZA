<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PSB SAZA</title>
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
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded bg-indigo-700 hover:bg-indigo-800 transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    👥 Kelola Pendaftar
                </a>
                <a href="{{ route('verifikasi-dokumen.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📋 Verifikasi Dokumen
                </a>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    💳 Item Pembayaran
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    💰 Kelola Pembayaran
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Laporan
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    ⚙️ Pengaturan
                </a>
            </nav>

            <hr class="my-6 border-indigo-400">

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button
                    type="submit"
                    class="w-full px-4 py-2 rounded bg-red-500 hover:bg-red-600 transition font-semibold text-sm"
                >
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow p-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <div class="text-right">
                    <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                    <p class="text-indigo-600 font-semibold text-sm">Admin</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Total Pendaftar</h3>
                        <p class="text-3xl font-bold text-indigo-600">--</p>
                        <p class="text-gray-500 text-xs mt-2">Bulan ini</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Menunggu Verifikasi</h3>
                        <p class="text-3xl font-bold text-yellow-600">--</p>
                        <p class="text-gray-500 text-xs mt-2">Dokumen</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Lolos Seleksi</h3>
                        <p class="text-3xl font-bold text-green-600">--</p>
                        <p class="text-gray-500 text-xs mt-2">Terkonfirmasi</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">Tidak Lolos</h3>
                        <p class="text-3xl font-bold text-red-600">--</p>
                        <p class="text-gray-500 text-xs mt-2">Ditolak</p>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                    <p class="text-gray-500 text-center py-8">Tidak ada aktivitas terbaru</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
