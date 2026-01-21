@extends('layouts.admin')

@section('title', 'Tambah Calon Santri')
@section('page-title', 'Tambah Calon Santri')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Tambah data calon santri baru ke sistem</p>
@endsection

@section('content')            <div class="bg-white shadow p-6 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Tambah Calon Santri Baru</h2>
                    <p class="text-sm text-gray-600 mt-1">Jenjang: <span class="font-bold text-indigo-600">{{ $jenjang }}</span></p>
                </div>
                <a href="{{ route('admin.calon-santri.index', ['jenjang' => $jenjang]) }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                    ← Kembali
                </a>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="bg-white rounded-lg shadow max-w-6xl">
                    <form method="POST" action="{{ route('admin.calon-santri.store') }}" class="p-8 space-y-8">
                        @csrf

                        <!-- Hidden Jenjang Field -->
                        <input type="hidden" name="jenjang" value="{{ $jenjang }}" />
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                                <p class="font-bold">Terjadi Kesalahan!</p>
                                <ul class="mt-2 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm">• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                            <p class="text-sm"><strong>ℹ️ Info:</strong> No. Pendaftaran akan di-generate otomatis dengan format PSB-TAHUN-NOMOR</p>
                        </div>

                        <!-- Section: Data Santri -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">📋 Data Santri</h3>
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                    <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Santri</label>
                                    <input type="text" name="nik_santri" value="{{ old('nik_santri') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Santri *</label>
                                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama lengkap" />
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kota/Kabupaten" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah *</label>
                                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="{{ $jenjang === 'MTs' ? 'SD/MI ...' : 'SMP/MTs ...' }}" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hobi</label>
                                    <input type="text" name="hobi" value="{{ old('hobi') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cita-cita</label>
                                    <input type="text" name="cita_cita" value="{{ old('cita_cita') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Alamat Lengkap -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">🏠 Alamat Lengkap</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="alamat" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <input type="text" name="provinsi" value="{{ old('provinsi') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten</label>
                                    <input type="text" name="kabupaten" value="{{ old('kabupaten') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Desa</label>
                                    <input type="text" name="desa" value="{{ old('desa') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Keluarga -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨‍👩‍👧‍👦 Data Keluarga</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Kartu Keluarga</label>
                                    <input type="text" name="no_kk" value="{{ old('no_kk') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Pendapatan Keluarga</label>
                                    <select name="pendapatan_keluarga" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\PendapatanKeluarga::orderBy('nama')->get() as $pendapatan)
                                            <option value="{{ $pendapatan->nama }}" {{ old('pendapatan_keluarga') === $pendapatan->nama ? 'selected' : '' }}>{{ $pendapatan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Saudara</label>
                                    <input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Ayah -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨 Data Ayah</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah *</label>
                                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ayah" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ayah</label>
                                    <input type="text" name="nik_ayah" value="{{ old('nik_ayah') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ayah</label>
                                    <select name="pendidikan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Pendidikan::orderBy('nama')->get() as $pendidikan)
                                            <option value="{{ $pendidikan->nama }}" {{ old('pendidikan_ayah') === $pendidikan->nama ? 'selected' : '' }}>{{ $pendidikan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah</label>
                                    <select name="pekerjaan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Pekerjaan::orderBy('nama')->get() as $pekerjaan)
                                            <option value="{{ $pekerjaan->nama }}" {{ old('pekerjaan_ayah') === $pekerjaan->nama ? 'selected' : '' }}>{{ $pekerjaan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HP Ayah</label>
                                    <input type="text" name="hp_ayah" value="{{ old('hp_ayah') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Ibu -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👩 Data Ibu</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu *</label>
                                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ibu" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ibu</label>
                                    <input type="text" name="nik_ibu" value="{{ old('nik_ibu') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
                                    <select name="pendidikan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Pendidikan::orderBy('nama')->get() as $pendidikan)
                                            <option value="{{ $pendidikan->nama }}" {{ old('pendidikan_ibu') === $pendidikan->nama ? 'selected' : '' }}>{{ $pendidikan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                                    <select name="pekerjaan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\App\Models\Pekerjaan::orderBy('nama')->get() as $pekerjaan)
                                            <option value="{{ $pekerjaan->nama }}" {{ old('pekerjaan_ibu') === $pekerjaan->nama ? 'selected' : '' }}>{{ $pekerjaan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HP Ibu</label>
                                    <input type="text" name="hp_ibu" value="{{ old('hp_ibu') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Kaitkan Akun -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">🔗 Kaitkan Akun (Nomor HP Orang Tua)</h3>
                            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                                <p class="text-sm"><strong>ℹ️ Info:</strong> Pilih nomor HP ayah atau ibu untuk dikaitkan dengan akun calon santri. Akun akan dibuat otomatis dengan password default: <strong>12345678</strong></p>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Kaitkan ke Nomor HP:</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="phone_type" value="ayah" class="mr-3" {{ old('phone_type') === 'ayah' ? 'checked' : '' }} />
                                            <span class="text-sm">📱 Nomor HP Ayah</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="phone_type" value="ibu" class="mr-3" {{ old('phone_type') === 'ibu' ? 'checked' : '' }} />
                                            <span class="text-sm">📱 Nomor HP Ibu</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="phone_type" value="" class="mr-3" {{ old('phone_type') === null || old('phone_type') === '' ? 'checked' : '' }} />
                                            <span class="text-sm">❌ Tidak Dikaitkan (Otomatis ke HP Ayah jika ada)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Status & Catatan -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">📊 Status & Catatan</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="baru" {{ old('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="proses" {{ old('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="lolos" {{ old('status') === 'lolos' ? 'selected' : '' }}>Lolos</option>
                                        <option value="tidak_lolos" {{ old('status') === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-6 border-t">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                                ✅ Simpan
                            </button>
                            <a href="{{ route('admin.calon-santri.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
@endsection
                    