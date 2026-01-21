@extends('layouts.santri')

@section('title', 'Dashboard Santri')
@section('page-title', 'Selamat Datang, ' . Auth::user()->name . '!')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Jenjang: <span class="font-semibold">{{ Auth::user()->jenjang }}</span> | No. HP: {{ Auth::user()->phone }}</p>
@endsection

@section('content')
    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-white rounded-lg shadow p-4 lg:p-6">
            <p class="text-gray-600 text-sm mb-2">Status Pendaftaran</p>
            @if($calonSantri)
                <p class="text-2xl lg:text-3xl font-bold text-green-600">✅ Sudah Input</p>
                <p class="text-xs text-gray-500 mt-2">No. Daftar: {{ $calonSantri->no_pendaftaran }}</p>
            @else
                <p class="text-2xl lg:text-3xl font-bold text-yellow-600">⏳ Belum Input</p>
                <p class="text-xs text-gray-500 mt-2">Lengkapi form dulu</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6">
            <p class="text-gray-600 text-sm mb-2">Jenjang Pilihan</p>
            <p class="text-2xl lg:text-3xl font-bold text-blue-600">{{ Auth::user()->jenjang }}</p>
            <p class="text-xs text-gray-500 mt-2">Terdaftar</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6">
            <p class="text-gray-600 text-sm mb-2">Status Pembayaran</p>
            @if($pembayaran)
                @if($pembayaran->status === 'lunas')
                    <p class="text-2xl lg:text-3xl font-bold text-green-600">✅ Lunas</p>
                @elseif($pembayaran->status === 'cicilan')
                    <p class="text-2xl lg:text-3xl font-bold text-yellow-600">🔄 Cicilan</p>
                @else
                    <p class="text-2xl lg:text-3xl font-bold text-red-600">❌ Belum Bayar</p>
                @endif
                <p class="text-xs text-gray-500 mt-2">Rp {{ number_format($pembayaran->remaining_amount, 0, ',', '.') }}</p>
            @else
                <p class="text-2xl lg:text-3xl font-bold text-gray-400">-</p>
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
    <div class="bg-white rounded-lg shadow p-4 lg:p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 lg:mb-6">🚀 Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            <!-- 1. Form Pendaftaran -->
            @if($calonSantri)
                <!-- Jika sudah ada data, langsung link ke form -->
                <a href="{{ route('santri.form-pendaftaran') }}" class="p-4 lg:p-6 border-2 border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-400 transition text-center">
                    <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">📋</div>
                    <p class="font-semibold text-gray-800 text-sm lg:text-base">Form Pendaftaran</p>
                    <p class="text-xs text-gray-500 mt-1 lg:mt-2">Edit data diri Anda</p>
                </a>
            @else
                <!-- Jika belum ada data, tampilkan modal dulu -->
                <button onclick="openDocsModal()" class="p-4 lg:p-6 border-2 border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-400 transition text-center">
                    <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">📋</div>
                    <p class="font-semibold text-gray-800 text-sm lg:text-base">Form Pendaftaran</p>
                    <p class="text-xs text-gray-500 mt-1 lg:mt-2">Isi data diri Anda</p>
                </button>
            @endif

            <!-- 2. Upload Dokumen -->
            @if($calonSantri)
                <a href="{{ route('santri.dokumen-upload') }}" class="p-4 lg:p-6 border-2 border-green-200 rounded-lg hover:bg-green-50 hover:border-green-400 transition text-center">
                    <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">📄</div>
                    <p class="font-semibold text-gray-800 text-sm lg:text-base">Upload Dokumen</p>
                    <p class="text-xs text-gray-500 mt-1 lg:mt-2">Dokumen persyaratan</p>
                </a>
            @else
                <div class="p-4 lg:p-6 border-2 border-gray-200 rounded-lg bg-gray-50 text-center opacity-60">
                    <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">📄</div>
                    <p class="font-semibold text-gray-400 text-sm lg:text-base">Upload Dokumen</p>
                    <p class="text-xs text-gray-400 mt-1 lg:mt-2">Lengkapi form dulu</p>
                </div>
            @endif

            <!-- 3. Pembayaran -->
            <a href="{{ route('santri.pembayaran') }}" class="p-4 lg:p-6 border-2 border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition text-center">
                <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">💳</div>
                <p class="font-semibold text-gray-800 text-sm lg:text-base">Pembayaran</p>
                <p class="text-xs text-gray-500 mt-1 lg:mt-2">Lihat tagihan & invoice</p>
            </a>

            <!-- 4. Cetak Bukti Daftar -->
            @if($calonSantri)
                <a href="{{ route('santri.print-bukti-pendaftaran', $calonSantri) }}" target="_blank" class="p-4 lg:p-6 border-2 border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-400 transition text-center">
                    <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">🖨️</div>
                    <p class="font-semibold text-gray-800 text-sm lg:text-base">Cetak Bukti Daftar</p>
                    <p class="text-xs text-gray-500 mt-1 lg:mt-2">Download & print</p>
                </a>
            @else
                <div class="p-4 lg:p-6 border-2 border-gray-200 rounded-lg bg-gray-50 text-center opacity-60">
                    <div class="text-3xl lg:text-4xl mb-2 lg:mb-3">🖨️</div>
                    <p class="font-semibold text-gray-400 text-sm lg:text-base">Cetak Bukti Daftar</p>
                    <p class="text-xs text-gray-400 mt-1 lg:mt-2">Belum tersedia</p>
                </div>
            @endif
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
            <li>Bayar biaya pendaftaran sesuai dengan tagihan</li>
            <li>Cetak bukti pendaftaran untuk disimpan</li>
            <li>Ikuti tes seleksi sesuai jadwal yang diumumkan</li>
        </ol>
    </div>

    <!-- Hardcopy Reminder Box -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mt-6">
        <p class="text-sm text-yellow-800">
            <span class="font-semibold">📬 Jangan Lupa Siapkan Hardcopy Dokumen!</span>
        </p>
        <p class="text-xs text-yellow-700 mt-2 mb-2">Selain upload digital, Anda perlu menyiapkan hardcopy (fotokopi) untuk diserahkan:</p>
        <ul class="text-xs text-yellow-700 ml-5 list-disc space-y-1">
            <li><strong>5 lembar fotokopi</strong> untuk setiap jenis dokumen</li>
            <li>Fotokopi harus jelas, rapi, dan mudah dibaca</li>
            <li>Bisa diantar langsung atau dikirim ke sekretariat pesantren</li>
            <li>Perhatikan jadwal pengumpulan yang diumumkan</li>
        </ul>
    </div>

    <!-- Modal Checklist Dokumen (hanya untuk akun baru) -->
    @if(!$calonSantri)
        <div id="docsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                <!-- Header -->
                <div class="bg-indigo-600 text-white p-4 lg:p-6 rounded-t-lg flex justify-between items-center">
                    <h2 class="text-lg lg:text-xl font-bold">📋 Siapkan Dokumen Anda</h2>
                    <button onclick="closeDocsModal()" class="text-2xl font-bold hover:text-indigo-200">&times;</button>
                </div>

                <!-- Content -->
                <div class="p-4 lg:p-6">
                    <p class="text-gray-700 font-semibold mb-4 text-sm lg:text-base">Sebelum mengisi form pendaftaran, pastikan Anda telah menyiapkan dokumen-dokumen berikut:</p>
                    
                    <div class="space-y-2 mb-6 text-sm">
                        <div class="text-gray-700">📄 Fotokopi Akta Kelahiran atau KTP</div>
                        <div class="text-gray-700">📄 Fotokopi Kartu Keluarga (KK)</div>
                        <div class="text-gray-700">📄 Raport Sekolah 2 Tahun Terakhir</div>
                        <div class="text-gray-700">📄 Surat Keterangan Lulus dari Sekolah</div>
                        <div class="text-gray-700">📄 Foto 4x6 (3 lembar)</div>
                        <div class="text-gray-700">📄 Sertifikat/Piagam Penghargaan (jika ada)</div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded mb-6">
                        <p class="text-xs text-yellow-700">
                            <strong>💡 Tips:</strong> Siapkan dokumen asli dan fotokopi. Fotokopi hendaknya jelas dan mudah dibaca.
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="closeDocsModal()" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 font-semibold transition text-sm">
                            ← Kembali
                        </button>
                        <a href="{{ route('santri.form-pendaftaran') }}" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold transition text-center text-sm">
                            Lanjutkan →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openDocsModal() {
            const modal = document.getElementById('docsModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeDocsModal() {
            const modal = document.getElementById('docsModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Close modal saat klik background
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('docsModal');
            if (modal && e.target === modal) {
                closeDocsModal();
            }
        });
    </script>
@endsection
