<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PSB Pesantren Modern Salsabiila Zainia - Penerimaan Santri Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-6xl w-full">
        <!-- Header Navigation -->
        <div class="text-right mb-8">
            @auth
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-block px-6 py-2 bg-white text-gray-700 rounded-lg hover:shadow-md transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-block px-6 py-2 text-gray-700 hover:text-gray-900 transition mr-2">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="inline-block px-6 py-2 bg-white text-gray-700 rounded-lg hover:shadow-md transition">
                    Register
                </a>
            @endauth
        </div>

        <!-- Main Content -->
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Info -->
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <div class="mb-8">
                    <div class="inline-block p-3 bg-blue-100 rounded-lg mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">PSB Pesantren Modern Salsabiila Zainia</h1>
                    <p class="text-xl text-gray-600 mb-6">Penerimaan Santri Baru<br>Pesantren Modern Salsabiila Zainia</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Untuk Calon Santri:</h2>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Daftar sebagai calon santri baru</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Lengkapi data pendaftaran</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Upload dokumen yang diperlukan</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Pantau status pendaftaran</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">Untuk Admin & Petugas:</h2>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Kelola data pendaftaran santri</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Verifikasi dokumen dan pembayaran</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700">Cetak laporan pendaftaran</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Side - CTA -->
            <div class="text-center md:text-left">
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Mulai Pendaftaran</h2>
                    <p class="text-gray-600 mb-8">
                        Daftarkan diri Anda sekarang dan bergabunglah dengan Pesantren Modern Salsabiila Zainia.
                    </p>
                    
                    <div class="space-y-4">
                        <a href="{{ route('register') }}" 
                           class="block w-full text-center px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition">
                            Daftar Sebagai Calon Santri
                        </a>
                        
                        <a href="{{ route('login') }}" 
                           class="block w-full text-center px-8 py-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                            Login ke Akun Anda
                        </a>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <p class="text-sm text-gray-500 text-center">
                            Sudah mendaftar? Gunakan akun Anda untuk login dan melanjutkan proses pendaftaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p>&copy; {{ date('Y') }} Pesantren Modern Salsabiila Zainia. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
