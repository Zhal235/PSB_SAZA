@extends('layouts.santri')

@section('title', 'Upload Dokumen')
@section('page-title', '📤 Upload Dokumen')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">No. Daftar: <span class="font-semibold">{{ $calonSantri->no_pendaftaran }}</span></p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow max-w-4xl">
        <div class="p-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
                <p class="text-sm"><strong>ℹ️ Info:</strong> Upload semua dokumen wajib. Gambar akan otomatis dikompres hingga 2MB.</p>
            </div>

            <!-- Upload Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($dokumenTypes as $value => $label)
                    @php
                        $dokumen = $calonSantri->dokumens()->where('tipe_dokumen', $value)->first();
                    @endphp
                    
                    <div class="border border-gray-300 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-800">{{ $label }}</h3>
                        
                        @if($dokumen)
                            <!-- Preview Document -->
                            <div class="bg-gray-100 rounded border border-gray-300 p-2 my-3 h-40 flex items-center justify-center overflow-hidden">
                                @php
                                    $ext = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                    $fileExists = \Storage::disk('public')->exists($dokumen->file_path);
                                @endphp
                                @if($fileExists && $isImage)
                                    <img src="{{ asset('storage/' . $dokumen->file_path) }}" alt="{{ $label }}" class="max-w-full max-h-full object-contain">
                                @elseif($fileExists)
                                    <div class="text-center">
                                        <p class="text-2xl">📄</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ strtoupper($ext) }}</p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <p class="text-2xl">⚠️</p>
                                        <p class="text-xs text-gray-600 mt-1">File tidak ditemukan</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Status -->
                            <div class="text-center">
                                @if($dokumen->hardcopy_diterima)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">✅ Terverifikasi</span>
                                @else
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">⏳ Menunggu</span>
                                @endif
                            </div>

                            <!-- Replace Button -->
                            <form action="{{ route('dokumen.update', [$calonSantri, $value]) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tipe_dokumen" value="{{ $value }}">
                                <label class="block">
                                    <input type="file" name="file" accept="image/*,.pdf" class="hidden" onchange="this.form.submit()" required>
                                    <span class="text-xs text-blue-600 hover:text-blue-800 font-semibold cursor-pointer">Ganti</span>
                                </label>
                            </form>
                        @else
                            <!-- Empty State -->
                            <div class="bg-gray-100 rounded border-2 border-dashed border-gray-300 p-2 my-3 h-40 flex items-center justify-center">
                                <div class="text-center">
                                    <p class="text-2xl">📁</p>
                                    <p class="text-xs text-gray-600 mt-1">Belum upload</p>
                                </div>
                            </div>

                            <!-- Upload Form -->
                            <form action="{{ route('dokumen.store', $calonSantri) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="tipe_dokumen" value="{{ $value }}">
                                <label class="block">
                                    <input type="file" name="file" accept="image/*,.pdf" class="hidden" onchange="this.form.submit()" required>
                                    <span class="text-xs text-blue-600 hover:text-blue-800 font-semibold cursor-pointer">+ Upload</span>
                                </label>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Info -->
            <div class="mt-8 p-4 bg-gray-50 rounded border border-gray-200">
                <p class="text-xs text-gray-600">
                    <strong>Persyaratan Dokumen:</strong>
                </p>
                <ul class="text-xs text-gray-600 mt-2 space-y-1">
                    <li>• <strong>Foto:</strong> Warna, ukuran 4x6 cm, latar belakang biru</li>
                    <li>• <strong>Ijazah:</strong> Scan full page, terang dan jelas</li>
                    <li>• <strong>Akte Kelahiran:</strong> Scan full page, terang dan jelas</li>
                    <li>• <strong>KTP Orang Tua:</strong> Scan kedua sisi, terang dan jelas</li>
                    <li>• <strong>Kartu Keluarga:</strong> Scan full page, terang dan jelas</li>
                </ul>
                <p class="text-xs text-gray-600 mt-3">
                    Format: JPG, PNG, PDF | Max: 5MB per file
                </p>
            </div>

            <!-- Navigation -->
            <div class="mt-8 flex gap-3">
                <a href="{{ route('santri.dashboard') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                    ← Kembali ke Dashboard
                </a>
                <a href="{{ route('santri.pembayaran') }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                    Lihat Pembayaran →
                </a>
            </div>
        </div>
    </div>
@endsection
