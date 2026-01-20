<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenjang - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-2xl px-4">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-indigo-600 mb-2">PSB SAZA</h1>
                <p class="text-gray-600 text-lg">Selamat Datang, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
                <p class="text-gray-500 text-sm mt-2">Silakan pilih jenjang pendidikan yang Anda inginkan</p>
            </div>

            <!-- Jenjang Selection -->
            <form method="POST" action="{{ route('santri.save-jenjang') }}" class="space-y-6">
                @csrf

                <!-- Jenjang Options -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- MTs Option -->
                    <label class="relative">
                        <input
                            type="radio"
                            name="jenjang"
                            value="MTs"
                            required
                            class="sr-only peer"
                        />
                        <div class="p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-400 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition">
                            <div class="text-center">
                                <div class="text-4xl mb-2">🏫</div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">Madrasah Tsanawiyah</h3>
                                <p class="text-sm text-gray-600">(MTs)</p>
                                <p class="text-xs text-gray-500 mt-3">Setara SMP</p>
                                <p class="text-xs text-gray-500">Durasi: 3 Tahun</p>
                            </div>
                        </div>
                    </label>

                    <!-- SMK Option -->
                    <label class="relative">
                        <input
                            type="radio"
                            name="jenjang"
                            value="SMK"
                            required
                            class="sr-only peer"
                        />
                        <div class="p-6 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-400 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition">
                            <div class="text-center">
                                <div class="text-4xl mb-2">🏢</div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">Sekolah Menengah Kejuruan</h3>
                                <p class="text-sm text-gray-600">(SMK)</p>
                                <p class="text-xs text-gray-500 mt-3">Setara SMK</p>
                                <p class="text-xs text-gray-500">Durasi: 3 Tahun</p>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-blue-700">
                        <span class="font-semibold">ℹ️ Informasi:</span> Pilihan jenjang ini akan menentukan program pendidikan yang Anda ikuti. Pastikan Anda memilih dengan benar.
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200"
                >
                    ✅ Pilih Jenjang
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p>© 2026 PSB SAZA - Semua Hak Dilindungi</p>
        </div>
    </div>
</body>
</html>
