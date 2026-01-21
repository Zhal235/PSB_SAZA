<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Mobile Menu Toggle -->
    <div class="lg:hidden bg-white shadow-md p-4 flex justify-between items-center" style="border-bottom: 4px solid #00a0a0;">
        <h1 class="text-xl font-bold" style="color: #007a7a;">PSB SAZA</h1>
        <button onclick="toggleMobileMenu()" class="p-2 rounded" style="background-color: #00a0a0; color: white;">
            <span id="menuIcon">☰</span>
        </button>
    </div>

    <div class="flex h-screen lg:h-screen">
        <!-- Sidebar (Desktop + Mobile Overlay) -->
        <div id="sidebar" class="fixed lg:relative inset-y-0 left-0 z-50 w-64 text-white p-6 flex flex-col overflow-y-auto shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300" style="background-color: #00a0a0;">
            <div class="mb-8 border-b border-white/20 pb-4">
                <h1 class="text-2xl font-bold">PSB SAZA</h1>
                <p class="text-white/80 text-sm mt-1">Portal Santri</p>
            </div>

            <nav class="space-y-2 flex-1">
                <a href="{{ route('santri.dashboard') }}" class="block px-4 py-3 rounded @if(Route::is('santri.dashboard')) @else hover:bg-white/20 @endif transition font-semibold text-sm" style="@if(Route::is('santri.dashboard')) background-color: #007a7a; @endif" onclick="closeMobileMenu()">
                    📊 Dashboard
                </a>
                <a href="{{ route('santri.form-pendaftaran') }}" class="block px-4 py-3 rounded @if(Route::is('santri.form-pendaftaran')) @else hover:bg-white/20 @endif transition font-semibold text-sm" style="@if(Route::is('santri.form-pendaftaran')) background-color: #007a7a; @endif" onclick="closeMobileMenu()">
                    📋 Form Pendaftaran
                </a>
                <a href="{{ route('santri.pembayaran') }}" class="block px-4 py-3 rounded @if(Route::is('santri.pembayaran')) @else hover:bg-white/20 @endif transition font-semibold text-sm" style="@if(Route::is('santri.pembayaran')) background-color: #007a7a; @endif" onclick="closeMobileMenu()">
                    💳 Pembayaran
                </a>
                <a href="{{ route('santri.dokumen-upload') }}" class="block px-4 py-3 rounded @if(Route::is('santri.dokumen-upload')) @else hover:bg-white/20 @endif transition font-semibold text-sm" style="@if(Route::is('santri.dokumen-upload')) background-color: #007a7a; @endif" onclick="closeMobileMenu()">
                    📄 Upload Dokumen
                </a>
                @php
                    $calonSantri = \App\Models\CalonSantri::where('no_telp', auth()->user()->phone)->first();
                @endphp
                @if($calonSantri)
                    <div class="relative group">
                        <button class="w-full text-left px-4 py-2 rounded hover:bg-white/20 transition font-semibold" style="color: #f0b43c;">
                            🖨️ Bukti Pendaftaran
                        </button>
                        <div class="hidden group-hover:block absolute left-0 mt-0 w-56 bg-white text-gray-800 rounded shadow-lg z-10">
                            <a href="{{ route('santri.print-bukti-pendaftaran', $calonSantri) }}" target="_blank" class="block px-4 py-2 hover:bg-gray-100 text-sm">
                                🖨️ Tampilkan & Print
                            </a>
                            <a href="{{ route('santri.download-bukti-pendaftaran', $calonSantri) }}" class="block px-4 py-2 hover:bg-gray-100 text-sm border-t">
                                📥 Download PDF
                            </a>
                        </div>
                    </div>
                @endif
            </nav>

            <hr class="my-6 border-white/20">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 rounded transition font-semibold text-sm" style="background-color: #f0b43c; color: #333;">
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Bar -->
            <div class="hidden lg:block bg-white shadow-md p-4 lg:p-6 flex justify-between items-center" style="border-bottom: 4px solid #00a0a0;">
                <div>
                    <h2 class="text-xl lg:text-2xl font-bold" style="color: #007a7a;">@yield('page-title')</h2>
                    @yield('page-subtitle')
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-xs lg:text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-[#00A651] font-semibold text-xs lg:text-sm">{{ Auth::user()->jenjang }}</p>
                </div>
            </div>

            <!-- Mobile Page Title -->
            <div class="lg:hidden bg-white shadow-sm p-4 border-b-2 border-gray-200">
                <h2 class="text-lg font-bold" style="color: #007a7a;">@yield('page-title')</h2>
                @yield('page-subtitle')
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-4 lg:p-8">
                    @if(session('success'))
                        <div class="bg-[#E8F5E9] border-l-4 border-[#00A651] text-[#1B5E20] p-3 lg:p-4 mb-4 lg:mb-6 rounded text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 mb-4 lg:mb-6 rounded text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="closeMobileMenu()"></div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const menuIcon = document.getElementById('menuIcon');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open menu
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                menuIcon.textContent = '✕';
            } else {
                // Close menu
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                menuIcon.textContent = '☰';
            }
        }

        function closeMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const menuIcon = document.getElementById('menuIcon');
            
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
            menuIcon.textContent = '☰';
        }

        // Close mobile menu on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
