<!-- Sidebar - Teal BSI -->
<div class="w-64 h-screen text-white p-6 flex flex-col shadow-2xl overflow-hidden" style="background-color: #00a0a0;">
    <!-- Header with Admin Info & Logout -->
    <div class="mb-8 border-b border-white/30 pb-4 flex-shrink-0">
        <div class="flex items-start justify-between gap-2 mb-3">
            <div class="flex-1">
                <h1 class="text-2xl font-bold drop-shadow-md">PSB Pesantren Modern Salsabiila Zainia</h1>
                <p class="text-white/80 text-xs mt-1 font-medium">Sistem Pendaftaran Santri</p>
            </div>
            <!-- Dropdown Logout -->
            <div class="relative group">
                <button class="px-2 py-1 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold text-lg" title="Menu Pengguna">
                    👤
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition-colors text-sm font-semibold flex items-center gap-2">
                            <span>➤</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation with Hidden Scrollbar -->
    <nav class="space-y-2 flex-1 overflow-y-auto" style="scrollbar-width: none; -ms-overflow-style: none;">
        <style>
            nav::-webkit-scrollbar {
                display: none;
            }
        </style>
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-white hover:shadow-lg transition-all duration-300 transform hover:-translate-x-1 font-semibold @if(Route::is('admin.dashboard')) shadow-lg @endif" style="@if(Route::is('admin.dashboard')) background-color: #007a7a; @endif">
            <span class="text-lg">▦</span> Dashboard
        </a>

        <!-- Kelola Pendaftar -->
        @if(auth()->user()->hasPermission('view-calon-santri') || auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.calon-santri.*')) bg-white/20 @endif">
                <span class="text-lg">⊙</span> Kelola Pendaftar
            </a>
        @endif

        <!-- Verifikasi Dokumen -->
        @if(auth()->user()->hasPermission('verify-dokumen') || auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.dokumen.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.dokumen.*')) bg-white/20 @endif">
                <span class="text-lg">▢</span> Verifikasi Dokumen
            </a>
        @endif

        <!-- Verifikasi Bukti Pembayaran -->
        @if(auth()->user()->hasAnyRole(['admin', 'petugas_keuangan']))
            <a href="{{ route('admin.bukti-pembayaran.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.bukti-pembayaran.*')) bg-white/20 @endif">
                <span class="text-lg">✓</span> Verifikasi Bukti Transfer
            </a>
        @endif

        <!-- Item Pembayaran (Hanya Admin) -->
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.pembayaran-items.*')) bg-white/20 @endif">
                <span class="text-lg">◆</span> Item Pembayaran
            </a>
        @endif

        <!-- Kelola Pembayaran -->
        @if(auth()->user()->hasPermission('view-pembayaran') || auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.pembayaran.*')) bg-white/20 @endif">
                <span class="text-lg">⬢</span> Kelola Pembayaran
            </a>
        @endif

        <!-- Pengaturan Bank (Hanya Admin) -->
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.bank-settings.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.bank-settings.*')) bg-white/20 @endif">
                <span class="text-lg">◇</span> Pengaturan Bank
            </a>
        @endif

        <!-- Financial Records (hanya untuk admin) -->
        @if(auth()->user()->hasPermission('view-financial-records') || auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.financial-records.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.financial-records.*')) bg-white/20 @endif">
                <span class="text-lg">◈</span> Pencatatan Keuangan
            </a>
        @endif

        <!-- User Petugas (Hanya Admin) -->
        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.users.*')) bg-white/20 @endif">
                <span class="text-lg">⊞</span> User Petugas
            </a>
        @endif

        <a href="#" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold">
            <span class="text-lg">▬</span> Laporan
        </a>
        <a href="#" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold">
            <span class="text-lg">⊗</span> Pengaturan
        </a>
    </nav>
</div>
