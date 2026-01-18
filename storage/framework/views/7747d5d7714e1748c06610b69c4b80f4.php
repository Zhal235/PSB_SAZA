<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenjang - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-indigo-600">PSB SAZA</h1>
                <p class="text-gray-600 text-sm mt-2">Penerimaan Santri Baru</p>
            </div>

            <!-- Question -->
            <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-8">
                <h2 class="text-lg font-bold text-gray-800">Pilih Jenjang Pendidikan</h2>
                <p class="text-gray-600 text-sm mt-2">Silakan pilih jenjang calon santri yang akan didaftarkan</p>
            </div>

            <!-- Buttons -->
            <div class="space-y-4">
                <!-- MTs Button -->
                <a href="<?php echo e(route('admin.calon-santri.create', ['jenjang' => 'MTs'])); ?>" class="block">
                    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105">
                        <div class="flex items-center justify-center">
                            <span class="text-2xl mr-3">🏫</span>
                            <div class="text-left">
                                <div class="font-bold text-lg">Madrasah Tsanawiyah (MTs)</div>
                                <div class="text-sm opacity-90">Pendaftaran Jenjang MTs</div>
                            </div>
                        </div>
                    </button>
                </a>

                <!-- SMK Button -->
                <a href="<?php echo e(route('admin.calon-santri.create', ['jenjang' => 'SMK'])); ?>" class="block">
                    <button class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105">
                        <div class="flex items-center justify-center">
                            <span class="text-2xl mr-3">🎓</span>
                            <div class="text-left">
                                <div class="font-bold text-lg">Sekolah Menengah Kejuruan (SMK)</div>
                                <div class="text-sm opacity-90">Pendaftaran Jenjang SMK</div>
                            </div>
                        </div>
                    </button>
                </a>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="<?php echo e(route('admin.calon-santri.index')); ?>" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    ← Kembali ke List
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>© 2026 PSB SAZA - Semua Hak Dilindungi</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Rhezal Maulana\Documents\GitHub\PSB_SAZA\resources\views/admin/calon-santri/select-jenjang.blade.php ENDPATH**/ ?>