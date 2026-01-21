<!-- Sidebar - Teal BSI -->
<div class="w-64 text-white p-6 flex flex-col shadow-2xl" style="background-color: #00a0a0;">
    <div class="mb-8 border-b border-white/30 pb-4">
        <h1 class="text-3xl font-bold drop-shadow-md">PSB SAZA</h1>
        <p class="text-white/80 text-sm mt-1 font-medium">Sistem Pendaftaran Santri</p>
    </div>

    <nav class="space-y-2 flex-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-white hover:shadow-lg transition-all duration-300 transform hover:-translate-x-1 font-semibold @if(Route::is('admin.dashboard')) shadow-lg @endif" style="@if(Route::is('admin.dashboard')) background-color: #007a7a; @endif">
            <span class="text-lg">▦</span> Dashboard
        </a>

        <!-- Kelola Pendaftar -->
        @if(auth()->user()->hasPermission('view-calon-santri') || auth()->user()->role === 'admin')
            <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.calon-santri.*')) bg-white/20 @endif">
                <span class="text-lg">⊙</span> Kelola Pendaftar
            </a>
        @endif

        <!-- Verifikasi Dokumen -->
        @if(auth()->user()->hasPermission('verify-dokumen') || auth()->user()->role === 'admin')
            <a href="{{ route('admin.dokumen.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.dokumen.*')) bg-white/20 @endif">
                <span class="text-lg">▢</span> Verifikasi Dokumen
            </a>
        @endif

        <!-- Verifikasi Bukti Pembayaran -->
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'petugas_keuangan')
            <a href="{{ route('admin.bukti-pembayaran.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.bukti-pembayaran.*')) bg-white/20 @endif">
                <span class="text-lg">✓</span> Verifikasi Bukti Transfer
            </a>
        @endif

        <!-- Item Pembayaran (Hanya Admin) -->
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.pembayaran-items.*')) bg-white/20 @endif">
                <span class="text-lg">◆</span> Item Pembayaran
            </a>
        @endif

        <!-- Kelola Pembayaran -->
        @if(auth()->user()->hasPermission('view-pembayaran') || auth()->user()->role === 'admin')
            <a href="{{ route('admin.pembayaran.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.pembayaran.*')) bg-white/20 @endif">
                <span class="text-lg">⬢</span> Kelola Pembayaran
            </a>
        @endif

        <!-- Pengaturan Bank (Hanya Admin) -->
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.bank-settings.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.bank-settings.*')) bg-white/20 @endif">
                <span class="text-lg">🏦</span> Pengaturan Bank
            </a>
        @endif

        <!-- Financial Records (hanya untuk admin) -->
        @if(auth()->user()->hasPermission('view-financial-records') || auth()->user()->role === 'admin')
            <a href="{{ route('admin.financial-records.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.financial-records.*')) bg-white/20 @endif">
                <span class="text-lg">💰</span> Pencatatan Keuangan
            </a>
        @endif

        <!-- User Petugas (Hanya Admin) -->
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold @if(Route::is('admin.users.*')) bg-white/20 @endif">
                <span class="text-lg">👥</span> User Petugas
            </a>
        @endif

        <a href="#" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold">
            <span class="text-lg">▬</span> Laporan
        </a>
        <a href="#" class="block px-4 py-3 rounded-lg text-white hover:bg-white/20 transition-all duration-300 font-semibold">
            <span class="text-lg">⊗</span> Pengaturan
        </a>
    </nav>

    <hr class="my-6 border-white/30">

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full px-4 py-3 rounded-lg hover:shadow-lg transition-all duration-300 font-semibold transform hover:-translate-y-0.5" style="background-color: #f0b43c; color: #333;">
            <span class="text-lg">➤</span> Logout
        </button>
    </form>
</div>
