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
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-4 gap-6 mb-8">
                        <!-- Card 1: Total Pendaftar -->
                        <div class="card-bsi bg-gradient-to-br from-white to-[#E8F5E9] p-6 border-l-4 border-[#00A651] group cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-600 text-sm font-medium mb-2">Total Pendaftar</h3>
                                    <p class="text-4xl font-bold text-[#00A651] group-hover:scale-110 transition-transform duration-300">--</p>
                                    <p class="text-gray-500 text-xs mt-2">Bulan ini</p>
                                </div>
                                <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">👥</div>
                            </div>
                        </div>

                        <!-- Card 2: Menunggu Verifikasi -->
                        <div class="card-bsi bg-gradient-to-br from-white to-[#FFF3E0] p-6 border-l-4 border-[#FF9900] group cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-600 text-sm font-medium mb-2">Menunggu Verifikasi</h3>
                                    <p class="text-4xl font-bold text-[#FF9900] group-hover:scale-110 transition-transform duration-300">--</p>
                                    <p class="text-gray-500 text-xs mt-2">Dokumen</p>
                                </div>
                                <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">⏳</div>
                            </div>
                        </div>

                        <!-- Card 3: Lolos Seleksi -->
                        <div class="card-bsi bg-gradient-to-br from-white to-[#E8F5E9] p-6 border-l-4 border-[#00A651] group cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-600 text-sm font-medium mb-2">Lolos Seleksi</h3>
                                    <p class="text-4xl font-bold text-[#00A651] group-hover:scale-110 transition-transform duration-300">--</p>
                                    <p class="text-gray-500 text-xs mt-2">Terkonfirmasi</p>
                                </div>
                                <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">✅</div>
                            </div>
                        </div>

                        <!-- Card 4: Tidak Lolos -->
                        <div class="card-bsi bg-gradient-to-br from-white to-[#FFEBEE] p-6 border-l-4 border-red-500 group cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-gray-600 text-sm font-medium mb-2">Tidak Lolos</h3>
                                    <p class="text-4xl font-bold text-red-600 group-hover:scale-110 transition-transform duration-300">--</p>
                                    <p class="text-gray-500 text-xs mt-2">Ditolak</p>
                                </div>
                                <div class="text-5xl opacity-20 group-hover:opacity-40 transition-opacity duration-300">❌</div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card-bsi bg-white p-6 border-l-4 border-[#00A651]">
                        <h3 class="text-xl font-bold bg-gradient-to-r from-[#00A651] to-[#003F87] bg-clip-text text-transparent mb-4">📊 Aktivitas Terbaru</h3>
                        <p class="text-gray-500 text-center py-12">Tidak ada aktivitas terbaru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
