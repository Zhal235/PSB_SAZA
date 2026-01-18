<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-600 text-white p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">PSB SAZA</h1>
                <p class="text-blue-200 text-sm">Santri Portal</p>
            </div>

            <nav class="space-y-2">
                <a href="#" class="block px-4 py-2 rounded bg-blue-700 hover:bg-blue-800 transition">
                    📊 Dashboard
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                    📝 Status Pendaftaran
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                    📄 Upload Dokumen
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                    📢 Pengumuman
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                    👤 Profil
                </a>
            </nav>

            <hr class="my-6 border-blue-400">

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
                    <p class="text-blue-600 font-semibold text-sm">Calon Santri</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Status Pendaftaran Anda</h3>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                        <p class="text-gray-700 font-semibold mb-2">Status: <span class="text-blue-600">Belum Lengkap</span></p>
                        <p class="text-gray-600 text-sm">Silakan lengkapi data dan upload dokumen yang diperlukan untuk menyelesaikan pendaftaran.</p>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">📅 Jadwal Tes</h3>
                        <p class="text-2xl font-bold text-blue-600">--</p>
                        <p class="text-gray-500 text-xs mt-2">Akan diumumkan</p>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-medium mb-2">📋 Dokumen</h3>
                        <p class="text-2xl font-bold text-yellow-600">0/5</p>
                        <p class="text-gray-500 text-xs mt-2">Terupload</p>
                    </div>
                </div>

                <!-- Recent Announcements -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">📢 Pengumuman Terbaru</h3>
                    <p class="text-gray-500 text-center py-8">Tidak ada pengumuman terbaru</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
