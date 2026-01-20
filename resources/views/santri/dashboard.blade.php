@extends('layouts.santri')

@section('title', 'Dashboard Santri')
@section('page-title', 'Selamat Datang, ' . Auth::user()->name . '!')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Jenjang: <span class="font-semibold">{{ Auth::user()->jenjang }}</span> | No. HP: {{ Auth::user()->phone }}</p>
@endsection

@section('content')
    <!-- Info Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm mb-2">Status Pendaftaran</p>
            @if($calonSantri)
                <p class="text-3xl font-bold text-green-600">✅ Sudah Input</p>
                <p class="text-xs text-gray-500 mt-2">No. Daftar: {{ $calonSantri->no_pendaftaran }}</p>
            @else
                <p class="text-3xl font-bold text-yellow-600">⏳ Belum Input</p>
                <p class="text-xs text-gray-500 mt-2">Lengkapi form dulu</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm mb-2">Jenjang Pilihan</p>
            <p class="text-3xl font-bold text-blue-600">{{ Auth::user()->jenjang }}</p>
            <p class="text-xs text-gray-500 mt-2">Terdaftar</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm mb-2">Status Pembayaran</p>
            @if($pembayaran)
                @if($pembayaran->status === 'lunas')
                    <p class="text-3xl font-bold text-green-600">✅ Lunas</p>
                @elseif($pembayaran->status === 'cicilan')
                    <p class="text-3xl font-bold text-yellow-600">🔄 Cicilan</p>
                @else
                    <p class="text-3xl font-bold text-red-600">❌ Belum Bayar</p>
                @endif
                <p class="text-xs text-gray-500 mt-2">Rp {{ number_format($pembayaran->remaining_amount, 0, ',', '.') }}</p>
            @else
                <p class="text-3xl font-bold text-gray-400">-</p>
                <p class="text-xs text-gray-500 mt-2">Belum ada data</p>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">🚀 Aksi Cepat</h3>
        <div class="grid grid-cols-3 gap-4">
            <a href="{{ route('santri.form-pendaftaran') }}" class="p-6 border-2 border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-400 transition text-center">
                <div class="text-4xl mb-3">📋</div>
                <p class="font-semibold text-gray-800">Form Pendaftaran</p>
                <p class="text-xs text-gray-500 mt-2">@if($calonSantri)Edit @else Isi @endif data diri Anda</p>
            </a>
            <a href="{{ route('santri.pembayaran') }}" class="p-6 border-2 border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-400 transition text-center">
                <div class="text-4xl mb-3">💳</div>
                <p class="font-semibold text-gray-800">Pembayaran</p>
                <p class="text-xs text-gray-500 mt-2">Lihat tagihan & invoice</p>
            </a>
            <a href="#" class="p-6 border-2 border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-400 transition text-center">
                <div class="text-4xl mb-3">📄</div>
                <p class="font-semibold text-gray-800">Upload Dokumen</p>
                <p class="text-xs text-gray-500 mt-2">Dokumen persyaratan</p>
            </a>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mt-8">
        <p class="text-sm text-blue-700">
            <span class="font-semibold">ℹ️ Langkah Pendaftaran:</span>
        </p>
        <ol class="text-sm text-blue-700 ml-5 mt-2 list-decimal space-y-1">
            <li>Lengkapi form pendaftaran dengan data diri yang benar</li>
            <li>Upload dokumen persyaratan yang diminta</li>
            <li>Lihat detail pembayaran dan bayar sesuai dengan tagihan</li>
            <li>Ikuti tes seleksi sesuai jadwal yang diumumkan</li>
        </ol>
    </div>
@endsection
