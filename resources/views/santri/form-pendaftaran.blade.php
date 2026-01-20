@extends('layouts.admin')

@section('title', 'Form Pendaftaran')
@section('page-title', '📋 Form Pendaftaran Santri')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Lengkapi data pendaftaran Anda untuk jenjang: <span class="font-semibold">{{ Auth::user()->jenjang }}</span></p>
@endsection

@section('content')
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Progress Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-2">Status Pendaftaran</p>
            @if($calonSantri)
                <p class="text-2xl font-bold text-green-600">✅ Sudah Input</p>
                <p class="text-xs text-gray-500 mt-2">No. Daftar: {{ $calonSantri->no_pendaftaran }}</p>
            @else
                <p class="text-2xl font-bold text-yellow-600">⏳ Belum Input</p>
                <p class="text-xs text-gray-500 mt-2">Silakan lengkapi form di bawah</p>
            @endif
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-2">No. HP Terdaftar</p>
            <p class="text-2xl font-bold text-indigo-600">{{ Auth::user()->phone }}</p>
            <p class="text-xs text-gray-500 mt-2">Data dari akun Anda</p>
        </div>

        <!-- Jenjang Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-600 mb-2">Jenjang Pilihan</p>
            <p class="text-2xl font-bold text-blue-600">{{ Auth::user()->jenjang }}</p>
            <p class="text-xs text-gray-500 mt-2">Sudah dikonfirmasi</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow p-8 max-w-6xl">
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-semibold">❌ Error:</p>
                <ul class="list-disc ml-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('santri.save-data') }}" class="space-y-8">
            @csrf

            <!-- Include form fields partial (reuse dari admin) -->
            @include('admin.calon-santri.form-fields', [
                'calonSantri' => $calonSantri,
                'showNoTelp' => false,
                'showStatusFields' => false
            ])

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <p class="text-sm text-blue-700">
                    <span class="font-semibold">ℹ️ Informasi:</span> Pastikan semua data yang diisi benar dan sesuai dengan dokumen. Data yang Anda input akan diverifikasi oleh admin.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                    ✅ @if($calonSantri)Update @else Daftar @endif Sekarang
                </button>
                <a href="{{ route('santri.dashboard') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                    ← Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
