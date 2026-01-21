@extends('layouts.admin')

@section('title', 'Tambah Petugas')
@section('page-title', '➕ Tambah Petugas Baru')
@section('page-subtitle', 'Buat akun untuk admin, petugas pendaftaran atau keuangan')

@section('content')
    <!-- Info Alert -->
    <div class="max-w-2xl mb-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <p class="text-sm text-yellow-700">
                <span class="font-semibold">⚠️ Penting:</span> Halaman ini untuk membuat akun petugas sistem, 
                <strong>BUKAN</strong> akun santri. Akun santri dibuat otomatis saat registrasi.
            </p>
        </div>
    </div>
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="user@example.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="role" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>👑 Admin</option>
                        <option value="petugas_pendaftaran" {{ old('role') === 'petugas_pendaftaran' ? 'selected' : '' }}>📋 Petugas Pendaftaran</option>
                        <option value="petugas_keuangan" {{ old('role') === 'petugas_keuangan' ? 'selected' : '' }}>💰 Petugas Keuangan</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="Ulangi password">
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-blue-700">
                        <span class="font-semibold">ℹ️ Info:</span> User akan otomatis terverifikasi dan dapat langsung login ke sistem.
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold transition">
                        ✅ Simpan User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 font-semibold transition">
                        ← Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Role Description -->
    <div class="max-w-2xl mt-8">
        <div class="bg-gray-50 rounded-lg p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4">📖 Penjelasan Role</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <span class="text-lg">👑</span>
                    <div>
                        <p class="font-semibold text-gray-800">Admin</p>
                        <p class="text-gray-600">Akses penuh ke semua fitur sistem termasuk manajemen user</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-lg">📋</span>
                    <div>
                        <p class="font-semibold text-gray-800">Petugas Pendaftaran</p>
                        <p class="text-gray-600">Mengelola data santri, verifikasi dokumen, dan proses pendaftaran</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-lg">💰</span>
                    <div>
                        <p class="font-semibold text-gray-800">Petugas Keuangan</p>
                        <p class="text-gray-600">Mengelola pembayaran, verifikasi transfer, dan laporan keuangan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection