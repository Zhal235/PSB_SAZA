@extends('layouts.admin')

@section('title', 'Edit Item Pembayaran')
@section('page-title', '✏️ Edit Item Pembayaran')

@section('top-bar-action')
    <a href="{{ route('admin.pembayaran-items.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">← Kembali</a>
@endsection

@section('content')
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

            <form method="POST" action="{{ route('admin.pembayaran-items.update', $pembayaranItem) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Item -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Item *</label>
                    <input type="text" name="nama" value="{{ old('nama', $pembayaranItem->nama) }}" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                <!-- Tipe Item (Pembayaran/Perlengkapan) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Kategori Item *</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="item_type" value="pembayaran" {{ old('item_type', $pembayaranItem->item_type) == 'pembayaran' ? 'checked' : '' }}>
                            <span class="text-indigo-600 font-semibold">💳 Pembayaran (SPP, Pendaftaran, dll)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="item_type" value="perlengkapan" {{ old('item_type', $pembayaranItem->item_type) == 'perlengkapan' ? 'checked' : '' }}>
                            <span class="text-amber-600 font-semibold">📦 Perlengkapan (Seragam, Sepatu, dll)</span>
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi', $pembayaranItem->deskripsi) }}</textarea>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp) *</label>
                    <input type="number" name="nominal" value="{{ old('nominal', $pembayaranItem->nominal) }}" step="any" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <p class="text-xs text-gray-600 mt-2">💡 Jika Anda mengubah harga, harga lama akan disimpan dan tanggal perubahan akan dicatat. Pendaftar baru akan dikenakan harga baru, sedangkan pendaftar lama tetap dengan harga lama.</p>
                </div>

                <!-- Riwayat Perubahan Harga -->
                @if($pembayaranItem->nominal_old && $pembayaranItem->effective_date)
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded">
                        <p class="font-semibold text-amber-800 mb-2">📊 Riwayat Perubahan Harga</p>
                        <div class="text-sm text-amber-700 space-y-1">
                            <p><strong>Harga Sebelumnya:</strong> Rp {{ number_format($pembayaranItem->nominal_old, 0, ',', '.') }}</p>
                            <p><strong>Harga Saat Ini:</strong> Rp {{ number_format($pembayaranItem->nominal, 0, ',', '.') }}</p>
                            <p><strong>Efektif Sejak:</strong> {{ \Carbon\Carbon::parse($pembayaranItem->effective_date)->format('d M Y') }}</p>
                            <p class="mt-3"><strong>Keterangan:</strong> Pendaftar sebelum {{ \Carbon\Carbon::parse($pembayaranItem->effective_date)->format('d M Y') }} akan dikenakan harga lama (Rp {{ number_format($pembayaranItem->nominal_old, 0, ',', '.') }}), sedangkan pendaftar mulai {{ \Carbon\Carbon::parse($pembayaranItem->effective_date)->format('d M Y') }} akan dikenakan harga baru (Rp {{ number_format($pembayaranItem->nominal, 0, ',', '.') }}).</p>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-sm text-blue-700">📌 Belum ada riwayat perubahan harga. Jika Anda mengubah harga di formulir di atas, sistem akan otomatis mencatatnya.</p>
                    </div>
                @endif

                <!-- Wajib/Optional -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tipe Item *</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_required" value="1" {{ old('is_required', $pembayaranItem->is_required) == '1' ? 'checked' : '' }} onchange="updateForm()">
                            <span class="text-red-600 font-semibold">🔴 Wajib</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_required" value="0" {{ old('is_required', $pembayaranItem->is_required) == '0' ? 'checked' : '' }} onchange="updateForm()">
                            <span class="text-blue-600 font-semibold">🔵 Opsional</span>
                        </label>
                    </div>
                </div>

                <!-- Cicilan -->
                <div>
                    <label class="flex items-center gap-2 mb-3">
                        <input type="checkbox" name="can_cicil" id="can_cicil" value="1" {{ old('can_cicil', $pembayaranItem->can_cicil) ? 'checked' : '' }} onchange="toggleCicil()">
                        <span class="text-sm font-semibold text-gray-700">✓ Bisa Dicicil</span>
                    </label>
                    <div id="cicil_section" class="{{ old('can_cicil', $pembayaranItem->can_cicil) ? '' : 'hidden' }}">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Maksimal Bulan Cicilan</label>
                        <select name="cicil_month" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih --</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('cicil_month', $pembayaranItem->cicil_month) == $i ? 'selected' : '' }}>
                                    {{ $i }} Bulan
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <option value="active" {{ old('status', $pembayaranItem->status) == 'active' ? 'selected' : '' }}>🟢 Aktif</option>
                        <option value="inactive" {{ old('status', $pembayaranItem->status) == 'inactive' ? 'selected' : '' }}>⚪ Inactive</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                        ✅ Update
                    </button>
                    <a href="{{ route('admin.pembayaran-items.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                        ❌ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
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
        </script>
    @endpush
@endsection
