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

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi', $pembayaranItem->deskripsi) }}</textarea>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp) *</label>
                    <input type="number" name="nominal" value="{{ old('nominal', $pembayaranItem->nominal) }}" step="1000" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

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
