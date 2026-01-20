@extends('layouts.admin')

@section('title', 'Edit Rekening Bank')
@section('page-title', '✏️ Edit Rekening Bank')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Ubah informasi rekening bank</p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow max-w-2xl">
        <div class="p-8">
            <form action="{{ route('admin.bank-settings.update', $bankSetting) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Bank Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank *</label>
                    <input type="text" name="bank_name" placeholder="Contoh: BCA, Mandiri, BNI, dll" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('bank_name', $bankSetting->bank_name) }}" required>
                    @error('bank_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening *</label>
                    <input type="text" name="account_number" placeholder="Contoh: 1234567890" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono" 
                        value="{{ old('account_number', $bankSetting->account_number) }}" required>
                    @error('account_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Holder -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Atas Nama (Pemilik Rekening) *</label>
                    <input type="text" name="account_holder" placeholder="Contoh: YAYASAN PENDIDIKAN ISLAM" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('account_holder', $bankSetting->account_holder) }}" required>
                    @error('account_holder')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi / Catatan</label>
                    <textarea name="description" placeholder="Contoh: Rekening untuk pendaftaran jalur reguler" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        rows="3">{{ old('description', $bankSetting->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP / Kontak</label>
                    <input type="text" name="phone" placeholder="Contoh: 0812345678" 
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        value="{{ old('phone', $bankSetting->phone) }}">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" 
                        class="w-4 h-4 text-blue-600" {{ $bankSetting->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-gray-700">
                        Aktifkan rekening ini (rekening yang aktif akan ditampilkan kepada santri)
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
                        ✅ Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.bank-settings.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold">
                        ❌ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
