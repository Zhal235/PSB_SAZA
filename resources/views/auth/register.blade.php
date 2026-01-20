<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-indigo-600">PSB SAZA</h1>
                <p class="text-gray-600 text-sm mt-2">Pendaftaran Santri Baru</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <p class="font-bold text-sm">❌ Pendaftaran Gagal!</p>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap *
                    </label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Contoh: Ahmad Rozi"
                    />
                </div>

                <!-- No HP -->
                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor HP *
                    </label>
                    <input
                        type="tel"
                        id="no_telp"
                        name="no_telp"
                        value="{{ old('no_telp') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Contoh: 08123456789"
                    />
                    <p class="text-xs text-gray-500 mt-1">Gunakan nomor HP yang aktif</p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password (min. 8 karakter) *
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Masukkan password"
                    />
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password *
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Ulangi password"
                    />
                </div>

                <!-- Persetujuan -->
                <div class="flex items-start mt-4">
                    <input
                        type="checkbox"
                        id="agree"
                        name="agree"
                        required
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-1"
                    />
                    <label for="agree" class="ml-2 block text-sm text-gray-700">
                        Saya setuju dengan <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Syarat & Ketentuan</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 mt-6"
                >
                    ✅ Daftar Akun
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Masuk di sini</a></p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm text-blue-700">
            <p class="font-semibold mb-2">ℹ️ Informasi Penting:</p>
            <ul class="list-disc ml-5 space-y-1">
                <li>Pastikan nomor HP dan email benar</li>
                <li>Gunakan password yang kuat dan mudah diingat</li>
                <li>Setelah daftar, Anda bisa langsung login</li>
            </ul>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>© 2026 PSB SAZA - Semua Hak Dilindungi</p>
        </div>
    </div>
</body>
</html>
