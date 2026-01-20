@extends('layouts.admin')

@section('title', 'Form Pendaftaran')
@section('page-title', '📋 Form Pendaftaran Santri')
@section('page-subtitle', '<p class="text-sm text-gray-600 mt-1">Lengkapi data pendaftaran Anda untuk jenjang: <span class="font-semibold">{{ Auth::user()->jenjang }}</span></p>')

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
    <div class="bg-white rounded-lg shadow p-8">
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

            <!-- Data Pribadi -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">📋 Data Pribadi</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama" value="{{ old('nama', $calonSantri->nama ?? '') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nama lengkap sesuai akta lahir" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih --</option>
                            <option value="laki-laki" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin ?? '') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir *</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $calonSantri->tempat_lahir ?? '') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Contoh: Jakarta" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $calonSantri->tanggal_lahir ?? '') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NISN (Opsional)</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $calonSantri->nisn ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nomor Induk Siswa Nasional" />
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">🏠 Alamat</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="alamat" rows="3" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('alamat', $calonSantri->alamat ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi *</label>
                        <input type="text" name="provinsi" value="{{ old('provinsi', $calonSantri->provinsi ?? '') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Contoh: DKI Jakarta" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kabupaten/Kota *</label>
                        <input type="text" name="kabupaten" value="{{ old('kabupaten', $calonSantri->kabupaten ?? '') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Contoh: Jakarta Pusat" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $calonSantri->kecamatan ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Kecamatan" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Desa/Kelurahan</label>
                        <input type="text" name="desa" value="{{ old('desa', $calonSantri->desa ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Desa/Kelurahan" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $calonSantri->kode_pos ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Contoh: 12110" />
                    </div>
                </div>
            </div>

            <!-- Data Sekolah -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">🎓 Data Sekolah</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Asal Sekolah *</label>
                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $calonSantri->asal_sekolah ?? '') }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nama sekolah asal" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hobi</label>
                        <input type="text" name="hobi" value="{{ old('hobi', $calonSantri->hobi ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Hobi Anda" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cita-cita</label>
                        <input type="text" name="cita_cita" value="{{ old('cita_cita', $calonSantri->cita_cita ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Cita-cita Anda" />
                    </div>
                </div>
            </div>

            <!-- Data Keluarga (Ringkas) -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">👨‍👩‍👦 Data Keluarga</h3>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Kartu Keluarga</label>
                        <input type="text" name="no_kk" value="{{ old('no_kk', $calonSantri->no_kk ?? '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nomor Kartu Keluarga" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Saudara</label>
                        <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara', $calonSantri->jumlah_saudara ?? '') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Berapa banyak saudara?" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendapatan Keluarga</label>
                        <select name="pendapatan_keluarga"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih --</option>
                            <option value="< 1 juta" {{ old('pendapatan_keluarga', $calonSantri->pendapatan_keluarga ?? '') == '< 1 juta' ? 'selected' : '' }}>< 1 juta</option>
                            <option value="1 - 2 juta" {{ old('pendapatan_keluarga', $calonSantri->pendapatan_keluarga ?? '') == '1 - 2 juta' ? 'selected' : '' }}>1 - 2 juta</option>
                            <option value="2 - 5 juta" {{ old('pendapatan_keluarga', $calonSantri->pendapatan_keluarga ?? '') == '2 - 5 juta' ? 'selected' : '' }}>2 - 5 juta</option>
                            <option value="> 5 juta" {{ old('pendapatan_keluarga', $calonSantri->pendapatan_keluarga ?? '') == '> 5 juta' ? 'selected' : '' }}>> 5 juta</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Ayah -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">👨 Data Ayah</h3>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Ayah *</label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $calonSantri->nama_ayah ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nama lengkap ayah" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIK Ayah</label>
                        <input type="text" name="nik_ayah" value="{{ old('nik_ayah', $calonSantri->nik_ayah ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nomor Induk Kependudukan" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Ayah</label>
                        <select name="pendidikan_ayah"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih --</option>
                            <option value="Tidak Sekolah" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                            <option value="SD" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D3" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_ayah', $calonSantri->pendidikan_ayah ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $calonSantri->pekerjaan_ayah ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Contoh: Wiraswasta, PNS, Buruh, dll" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">HP Ayah</label>
                        <input type="tel" name="hp_ayah" value="{{ old('hp_ayah', $calonSantri->hp_ayah ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

            <!-- Data Ibu -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">👩 Data Ibu</h3>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Ibu *</label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $calonSantri->nama_ibu ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nama lengkap ibu" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIK Ibu</label>
                        <input type="text" name="nik_ibu" value="{{ old('nik_ibu', $calonSantri->nik_ibu ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Nomor Induk Kependudukan" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Ibu</label>
                        <select name="pendidikan_ibu"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih --</option>
                            <option value="Tidak Sekolah" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                            <option value="SD" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D3" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $calonSantri->pekerjaan_ibu ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="Contoh: Ibu Rumah Tangga, PNS, Buruh, dll" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">HP Ibu</label>
                        <input type="tel" name="hp_ibu" value="{{ old('hp_ibu', $calonSantri->hp_ibu ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                            placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </div>

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
