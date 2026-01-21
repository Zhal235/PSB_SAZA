@extends('layouts.santri')

@section('title', 'Form Pendaftaran')
@section('page-title', '📋 Form Pendaftaran Santri')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Lengkapi data pendaftaran Anda untuk jenjang: <span class="font-semibold">{{ Auth::user()->jenjang }}</span></p>
@endsection

@section('content')
    <!-- Progress Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Progress Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6">
            <p class="text-sm text-gray-600 mb-2">Status Pendaftaran</p>
            @if($calonSantri)
                <p class="text-xl lg:text-2xl font-bold text-green-600">✅ Sudah Input</p>
                <p class="text-xs text-gray-500 mt-2">No. Daftar: {{ $calonSantri->no_pendaftaran }}</p>
            @else
                <p class="text-xl lg:text-2xl font-bold text-yellow-600">⏳ Belum Input</p>
                <p class="text-xs text-gray-500 mt-2">Silakan lengkapi form di bawah</p>
            @endif
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6">
            <p class="text-sm text-gray-600 mb-2">No. HP Terdaftar</p>
            <p class="text-xl lg:text-2xl font-bold text-indigo-600">{{ Auth::user()->phone }}</p>
            <p class="text-xs text-gray-500 mt-2">Data dari akun Anda</p>
        </div>

        <!-- Jenjang Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6">
            <p class="text-sm text-gray-600 mb-2">Jenjang Pilihan</p>
            <p class="text-xl lg:text-2xl font-bold text-blue-600">{{ Auth::user()->jenjang }}</p>
            <p class="text-xs text-gray-500 mt-2">Sudah dikonfirmasi</p>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow p-4 lg:p-8">
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 mb-4 lg:mb-6 rounded">
                <p class="font-semibold text-sm">❌ Error:</p>
                <ul class="list-disc ml-5 mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 mb-4 lg:mb-6 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Progress Steps -->
        <div class="mb-6 lg:mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-4">
                <h2 class="text-lg lg:text-xl font-bold text-gray-800 mb-4 lg:mb-0">Lengkapi Data Pendaftaran</h2>
                <div class="text-sm text-gray-600 bg-gray-50 px-3 py-1 rounded">
                    📝 Klik setiap bagian untuk mengisi data
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('santri.save-data') }}" class="space-y-4 lg:space-y-6">
            @csrf

            <!-- Step 1: Data Santri -->
            <div class="accordion-section border border-gray-200 rounded-lg">
                <div class="accordion-header bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-t-lg cursor-pointer" onclick="toggleSection('step1')">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                            <h3 class="text-base lg:text-lg font-bold text-gray-800">👤 Data Santri</h3>
                        </div>
                        <span class="text-indigo-600 font-bold text-xl" id="step1-icon">-</span>
                    </div>
                </div>
                <div id="step1-content" class="accordion-content p-4 lg:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NISN (Opsional)</label>
                            <input type="text" name="nisn" value="{{ old('nisn', $calonSantri->nisn ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NISN jika ada" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIK Santri (Opsional)</label>
                            <input type="text" name="nik_santri" value="{{ old('nik_santri', $calonSantri->nik_santri ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIK jika ada" />
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $calonSantri->nama ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama lengkap sesuai dokumen" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih --</option>
                                <option value="laki-laki" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin ?? '') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin ?? '') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $calonSantri->tempat_lahir ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kota/Kabupaten" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($calonSantri)->tanggal_lahir ? $calonSantri->tanggal_lahir->format('Y-m-d') : '') }}" required onkeydown="return false" onpaste="return false" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $calonSantri->asal_sekolah ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ketik nama sekolah" list="sekolah-list" />
                            <datalist id="sekolah-list">
                                @php
                                    $sekolahs = \App\Models\Sekolah::orderBy('nama')->get();
                                @endphp
                                @foreach($sekolahs as $sekolah)
                                    <option value="{{ $sekolah->nama }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hobi (Opsional)</label>
                            <input type="text" name="hobi" value="{{ old('hobi', $calonSantri->hobi ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Hobi/minat" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cita-cita (Opsional)</label>
                            <input type="text" name="cita_cita" value="{{ old('cita_cita', $calonSantri->cita_cita ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Cita-cita" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Alamat -->
            <div class="accordion-section border border-gray-200 rounded-lg">
                <div class="accordion-header bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-t-lg cursor-pointer" onclick="toggleSection('step2')">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                            <h3 class="text-base lg:text-lg font-bold text-gray-800">🏠 Alamat Lengkap</h3>
                        </div>
                        <span class="text-green-600 font-bold text-xl" id="step2-icon">+</span>
                    </div>
                </div>
                <div id="step2-content" class="accordion-content p-4 lg:p-6 hidden">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap">{{ old('alamat', $calonSantri->alamat ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                                <input type="text" name="provinsi" value="{{ old('provinsi', $calonSantri->provinsi ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Provinsi" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten <span class="text-red-500">*</span></label>
                                <input type="text" name="kabupaten" value="{{ old('kabupaten', $calonSantri->kabupaten ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kabupaten/Kota" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan', $calonSantri->kecamatan ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kecamatan" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Desa/Kelurahan</label>
                                <input type="text" name="desa" value="{{ old('desa', $calonSantri->desa ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Desa/Kelurahan" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', $calonSantri->kode_pos ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kode Pos" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Data Keluarga -->
            <div class="accordion-section border border-gray-200 rounded-lg">
                <div class="accordion-header bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-t-lg cursor-pointer" onclick="toggleSection('step3')">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                            <h3 class="text-base lg:text-lg font-bold text-gray-800">👨‍👩‍👧‍👦 Data Keluarga</h3>
                        </div>
                        <span class="text-purple-600 font-bold text-xl" id="step3-icon">+</span>
                    </div>
                </div>
                <div id="step3-content" class="accordion-content p-4 lg:p-6 hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Kartu Keluarga</label>
                            <input type="text" name="no_kk" value="{{ old('no_kk', $calonSantri->no_kk ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="No. KK" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendapatan Keluarga</label>
                            <select name="pendapatan_keluarga" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih --</option>
                                @php
                                    $pendapatans = \App\Models\PendapatanKeluarga::orderBy('nama')->get();
                                @endphp
                                @foreach($pendapatans as $pendapatan)
                                    <option value="{{ $pendapatan->nama }}" {{ old('pendapatan_keluarga', $calonSantri->pendapatan_keluarga ?? '') === $pendapatan->nama ? 'selected' : '' }}>{{ $pendapatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Saudara</label>
                            <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara', $calonSantri->jumlah_saudara ?? '') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" />
                        </div>
                    </div>

                    <!-- Data Ayah -->
                    <div class="mt-6 border-t pt-6">
                        <h4 class="text-md font-bold text-gray-700 mb-4">👨 Data Ayah</h4>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $calonSantri->nama_ayah ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ayah" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ayah</label>
                                <input type="text" name="nik_ayah" value="{{ old('nik_ayah', $calonSantri->nik_ayah ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIK" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">HP Ayah</label>
                                <input type="text" name="hp_ayah" value="{{ old('hp_ayah', $calonSantri->hp_ayah ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ayah</label>
                                <select name="pendidikan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    @php
                                        $pendidikans = \App\Models\Pendidikan::orderBy('nama')->get();
                                    @endphp
                                    @foreach($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->nama }}" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') === $pendidikan->nama ? 'selected' : '' }}>{{ $pendidikan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah</label>
                                <select name="pekerjaan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    @php
                                        $pekerjaans = \App\Models\Pekerjaan::orderBy('nama')->get();
                                    @endphp
                                    @foreach($pekerjaans as $pekerjaan)
                                        <option value="{{ $pekerjaan->nama }}" {{ old('pekerjaan_ayah', $calonSantri->pekerjaan_ayah ?? '') === $pekerjaan->nama ? 'selected' : '' }}>{{ $pekerjaan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Data Ibu -->
                    <div class="mt-6 border-t pt-6">
                        <h4 class="text-md font-bold text-gray-700 mb-4">👩 Data Ibu</h4>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $calonSantri->nama_ibu ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ibu" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ibu</label>
                                <input type="text" name="nik_ibu" value="{{ old('nik_ibu', $calonSantri->nik_ibu ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIK" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">HP Ibu</label>
                                <input type="text" name="hp_ibu" value="{{ old('hp_ibu', $calonSantri->hp_ibu ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
                                <select name="pendidikan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->nama }}" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') === $pendidikan->nama ? 'selected' : '' }}>{{ $pendidikan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                                <select name="pekerjaan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Pilih --</option>
                                    @foreach($pekerjaans as $pekerjaan)
                                        <option value="{{ $pekerjaan->nama }}" {{ old('pekerjaan_ibu', $calonSantri->pekerjaan_ibu ?? '') === $pekerjaan->nama ? 'selected' : '' }}>{{ $pekerjaan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 lg:p-4 rounded">
                <p class="text-sm text-blue-700">
                    <span class="font-semibold">ℹ️ Informasi:</span> Pastikan semua data yang diisi benar dan sesuai dengan dokumen. Data yang Anda input akan diverifikasi oleh admin.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 lg:pt-6 border-t">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 font-semibold transition text-sm">
                    ✅ @if($calonSantri)Update @else Daftar @endif Sekarang
                </button>
                <a href="{{ route('santri.dashboard') }}" class="bg-gray-400 text-white px-6 py-3 rounded hover:bg-gray-500 font-semibold transition text-center text-sm">
                    ← Kembali
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleSection(stepId) {
            const content = document.getElementById(stepId + '-content');
            const icon = document.getElementById(stepId + '-icon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.textContent = '-';
            } else {
                content.classList.add('hidden');
                icon.textContent = '+';
            }
        }

        // Expand first section by default
        document.addEventListener('DOMContentLoaded', function() {
            // First section is already open
        });
    </script>
@endsection