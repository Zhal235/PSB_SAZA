<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Item Pembayaran - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-indigo-600 text-white p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">PSB SAZA</h1>
                <p class="text-indigo-200 text-sm">Admin Panel</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="block px-4 py-2 rounded bg-indigo-700 transition font-semibold">
                    💳 Item Pembayaran
                </a>
            </nav>

            <hr class="my-6 border-indigo-400">

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="w-full px-4 py-2 rounded bg-red-500 hover:bg-red-600 transition font-semibold text-sm">
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow p-6 flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-2xl font-bold text-gray-800">➕ Tambah Item Pembayaran</h2>
                <a href="{{ route('admin.pembayaran-items.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">← Kembali</a>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="bg-white rounded-lg shadow max-w-2xl">
                    <div class="p-8">
                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                                <p class="font-semibold">❌ Error:</p>
                                <ul class="list-disc ml-5 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.pembayaran-items.store') }}" class="space-y-6">
                            @csrf

                            <!-- Nama Item -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Item *</label>
                                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Pendaftaran, SPP, Seragam" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (opsional)</label>
                                <textarea name="deskripsi" rows="3" placeholder="Deskripsi item pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi') }}</textarea>
                            </div>

                            <!-- Nominal -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp) *</label>
                                <input type="number" name="nominal" value="{{ old('nominal') }}" placeholder="0" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <!-- Wajib/Optional -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Tipe Item *</label>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="is_required" value="1" {{ old('is_required', '1') == '1' ? 'checked' : '' }} onchange="updateForm()">
                                        <span class="text-red-600 font-semibold">🔴 Wajib (Required)</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="is_required" value="0" {{ old('is_required') == '0' ? 'checked' : '' }} onchange="updateForm()">
                                        <span class="text-blue-600 font-semibold">🔵 Opsional (Optional)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Cicilan -->
                            <div>
                                <label class="flex items-center gap-2 mb-3">
                                    <input type="checkbox" name="can_cicil" id="can_cicil" value="1" {{ old('can_cicil') ? 'checked' : '' }} onchange="toggleCicil()">
                                    <span class="text-sm font-semibold text-gray-700">✓ Bisa Dicicil</span>
                                </label>
                                <div id="cicil_section" class="{{ old('can_cicil') ? '' : 'hidden' }}">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maksimal Bulan Cicilan</label>
                                    <select name="cicil_month" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('cicil_month') == $i ? 'selected' : '' }}>
                                                {{ $i }} Bulan
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-3 pt-6 border-t">
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                                    ✅ Simpan
                                </button>
                                <a href="{{ route('admin.pembayaran-items.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                                    ❌ Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCicil() {
            const checkbox = document.getElementById('can_cicil');
            const section = document.getElementById('cicil_section');
            if (checkbox.checked) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }

        function updateForm() {
            // Untuk future validation
        }
    </script>
</body>
</html>
