<!-- Form Fields Partial - Used by both Admin and Santri -->

<!-- Section: Data Santri -->
<div>
    <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">📋 Data Santri</h3>
    <div class="grid grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
            <input type="text" name="nisn" value="{{ old('nisn', $calonSantri->nisn ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">NIK Santri</label>
            <input type="text" name="nik_santri" value="{{ old('nik_santri', $calonSantri->nik_santri ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Santri *</label>
            <input type="text" name="nama" value="{{ old('nama', $calonSantri->nama ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama lengkap" />
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
            <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Pilih --</option>
                <option value="laki-laki" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin ?? '') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin ?? '') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $calonSantri->tempat_lahir ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kota/Kabupaten" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($calonSantri)->tanggal_lahir ? $calonSantri->tanggal_lahir->format('Y-m-d') : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" onkeydown="return false" onpaste="return false" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah *</label>
            <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $calonSantri->asal_sekolah ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Ketik nama sekolah" list="sekolah-list" />
            <datalist id="sekolah-list">
                @php
                    $sekolahs = \App\Models\Sekolah::orderBy('nama')->get();
                @endphp
                @foreach($sekolahs as $sekolah)
                    <option value="{{ $sekolah->nama }}"></option>
                @endforeach
            </datalist>
            <p class="text-xs text-gray-500 mt-2">Ketik nama sekolah atau pilih dari saran yang tersedia</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Hobi</label>
            <input type="text" name="hobi" value="{{ old('hobi', $calonSantri->hobi ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cita-cita</label>
            <input type="text" name="cita_cita" value="{{ old('cita_cita', $calonSantri->cita_cita ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
        </div>
    </div>
</div>

<!-- Section: Alamat Lengkap -->
<div>
    <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">🏠 Alamat Lengkap</h3>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
        <textarea name="alamat" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap">{{ old('alamat', $calonSantri->alamat ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
            <input type="text" name="provinsi" value="{{ old('provinsi', $calonSantri->provinsi ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Provinsi" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten *</label>
            <input type="text" name="kabupaten" value="{{ old('kabupaten', $calonSantri->kabupaten ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kabupaten/Kota" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
            <input type="text" name="kecamatan" value="{{ old('kecamatan', $calonSantri->kecamatan ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kecamatan" />
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Desa</label>
            <input type="text" name="desa" value="{{ old('desa', $calonSantri->desa ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Desa/Kelurahan" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
            <input type="text" name="kode_pos" value="{{ old('kode_pos', $calonSantri->kode_pos ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kode Pos" />
        </div>
        @if($showNoTelp ?? true)
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $calonSantri->no_telp ?? auth()->user()->phone ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
            </div>
        @endif
    </div>
</div>

<!-- Section: Data Keluarga -->
<div>
    <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨‍👩‍👧‍👦 Data Keluarga</h3>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">No. Kartu Keluarga</label>
            <input type="text" name="no_kk" value="{{ old('no_kk', $calonSantri->no_kk ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="No. KK" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Pendapatan Keluarga</label>
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
</div>

<!-- Section: Data Ayah -->
<div>
    <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨 Data Ayah</h3>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah *</label>
            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $calonSantri->nama_ayah ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ayah" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ayah</label>
            <input type="text" name="nik_ayah" value="{{ old('nik_ayah', $calonSantri->nik_ayah ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIK" />
        </div>
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
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4">
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
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">HP Ayah</label>
            <input type="text" name="hp_ayah" value="{{ old('hp_ayah', $calonSantri->hp_ayah ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
        </div>
    </div>
</div>

<!-- Section: Data Ibu -->
<div>
    <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👩 Data Ibu</h3>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu *</label>
            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $calonSantri->nama_ibu ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ibu" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ibu</label>
            <input type="text" name="nik_ibu" value="{{ old('nik_ibu', $calonSantri->nik_ibu ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="NIK" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
            <select name="pendidikan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Pilih --</option>
                @php
                    $pendidikans = \App\Models\Pendidikan::orderBy('nama')->get();
                @endphp
                @foreach($pendidikans as $pendidikan)
                    <option value="{{ $pendidikan->nama }}" {{ old('pendidikan_ibu', $calonSantri->pendidikan_ibu ?? '') === $pendidikan->nama ? 'selected' : '' }}>{{ $pendidikan->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
            <select name="pekerjaan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Pilih --</option>
                @php
                    $pekerjaans = \App\Models\Pekerjaan::orderBy('nama')->get();
                @endphp
                @foreach($pekerjaans as $pekerjaan)
                    <option value="{{ $pekerjaan->nama }}" {{ old('pekerjaan_ibu', $calonSantri->pekerjaan_ibu ?? '') === $pekerjaan->nama ? 'selected' : '' }}>{{ $pekerjaan->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">HP Ibu</label>
            <input type="text" name="hp_ibu" value="{{ old('hp_ibu', $calonSantri->hp_ibu ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
        </div>
    </div>
</div>

@if($showStatusFields ?? false)
    <!-- Section: Status & Catatan (hanya untuk admin) -->
    <div>
        <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">📊 Status & Catatan</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih --</option>
                    <option value="baru" {{ old('status', $calonSantri->status ?? '') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="proses" {{ old('status', $calonSantri->status ?? '') === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="lolos" {{ old('status', $calonSantri->status ?? '') === 'lolos' ? 'selected' : '' }}>Lolos</option>
                    <option value="tidak_lolos" {{ old('status', $calonSantri->status ?? '') === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
            <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $calonSantri->catatan ?? '') }}</textarea>
        </div>
    </div>
@endif
