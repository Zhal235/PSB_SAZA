<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar - BSI Primary Green -->
        <div class="w-64 bg-[#00A651] text-white p-6 flex flex-col overflow-y-auto shadow-lg">
            <div class="mb-8 border-b border-white/20 pb-4">
                <h1 class="text-3xl font-bold">PSB SAZA</h1>
                <p class="text-[#E8F5E9] text-sm mt-1">Sistem Pendaftaran Santri</p>
            </div>

            <nav class="space-y-2 flex-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded @if(Route::is('admin.dashboard')) bg-[#003F87] @else hover:bg-[#003F87] @endif transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-2 rounded @if(Route::is('admin.calon-santri.*')) bg-[#003F87] @else hover:bg-[#003F87] @endif transition">
                    👥 Kelola Pendaftar
                </a>
                <a href="{{ route('verifikasi-dokumen.index') }}" class="block px-4 py-2 rounded @if(Route::is('verifikasi-dokumen.*')) bg-[#003F87] @else hover:bg-[#003F87] @endif transition">
                    📋 Verifikasi Dokumen
                </a>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-2 rounded @if(Route::is('admin.pembayaran-items.*')) bg-[#003F87] @else hover:bg-[#003F87] @endif transition">
                    💳 Item Pembayaran
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-2 rounded @if(Route::is('admin.pembayaran.*')) bg-[#003F87] @else hover:bg-[#003F87] @endif transition">
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
                <button type="submit" class="w-full px-4 py-2 rounded bg-[#FF9900] hover:bg-[#E68A00] transition font-semibold text-sm text-white">
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white shadow-md p-6 flex justify-between items-center border-b-4 border-[#00A651]">
                <div>
                    <h2 class="text-2xl font-bold text-[#003F87]">@yield('page-title')</h2>
                    @yield('page-subtitle')
                </div>
                @yield('top-bar-action')
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    @if(session('success'))
                        <div class="bg-[#E8F5E9] border-l-4 border-[#00A651] text-[#1B5E20] p-4 mb-6 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
