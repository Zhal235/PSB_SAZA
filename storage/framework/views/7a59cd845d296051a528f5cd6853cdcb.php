<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Calon Santri - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-indigo-600 text-white p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">PSB SAZA</h1>
                <p class="text-indigo-200 text-sm">Admin Panel</p>
            </div>

            <nav class="space-y-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Dashboard
                </a>
                <a href="<?php echo e(route('admin.calon-santri.index')); ?>" class="block px-4 py-2 rounded bg-indigo-700 hover:bg-indigo-800 transition font-semibold">
                    👥 Kelola Pendaftar
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📋 Verifikasi Dokumen
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Laporan
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    ⚙️ Pengaturan
                </a>
            </nav>

            <hr class="my-6 border-indigo-400">

            <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-auto">
                <?php echo csrf_field(); ?>
                <button
                    type="submit"
                    class="w-full px-4 py-2 rounded bg-red-500 hover:bg-red-600 transition font-semibold text-sm"
                >
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow p-6 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Edit Calon Santri: <?php echo e($calonSantri->nama); ?></h2>
                    <p class="text-sm text-gray-600 mt-1">Jenjang: <span class="font-bold text-indigo-600"><?php echo e($calonSantri->jenjang); ?></span></p>
                </div>
                <a href="<?php echo e(route('admin.calon-santri.index', ['jenjang' => $calonSantri->jenjang])); ?>" class="text-gray-600 hover:text-gray-800 font-semibold">
                    ← Kembali
                </a>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="bg-white rounded-lg shadow max-w-6xl">
                    <form method="POST" action="<?php echo e(route('admin.calon-santri.update', $calonSantri)); ?>" class="p-8 space-y-8">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Error Messages -->
                        <?php if($errors->any()): ?>
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                                <p class="font-bold">Terjadi Kesalahan!</p>
                                <ul class="mt-2 space-y-1">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="text-sm">• <?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Section: Data Santri -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">📋 Data Santri</h3>
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                    <input type="text" name="nisn" value="<?php echo e(old('nisn', $calonSantri->nisn)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Santri</label>
                                    <input type="text" name="nik_santri" value="<?php echo e(old('nik_santri', $calonSantri->nik_santri)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Santri *</label>
                                    <input type="text" name="nama" value="<?php echo e(old('nama', $calonSantri->nama)); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama lengkap" />
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="laki-laki" <?php echo e(old('jenis_kelamin', $calonSantri->jenis_kelamin) === 'laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="perempuan" <?php echo e(old('jenis_kelamin', $calonSantri->jenis_kelamin) === 'perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $calonSantri->tempat_lahir)); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kota/Kabupaten" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $calonSantri->tanggal_lahir->format('Y-m-d'))); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah *</label>
                                    <input type="text" name="asal_sekolah" value="<?php echo e(old('asal_sekolah', $calonSantri->asal_sekolah)); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="<?php echo e($calonSantri->jenjang === 'MTs' ? 'SD/MI ...' : 'SMP/MTs ...'); ?>" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hobi</label>
                                    <input type="text" name="hobi" value="<?php echo e(old('hobi', $calonSantri->hobi)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cita-cita</label>
                                    <input type="text" name="cita_cita" value="<?php echo e(old('cita_cita', $calonSantri->cita_cita)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Alamat Lengkap -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">🏠 Alamat Lengkap</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="alamat" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap"><?php echo e(old('alamat', $calonSantri->alamat)); ?></textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <input type="text" name="provinsi" value="<?php echo e(old('provinsi', $calonSantri->provinsi)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten</label>
                                    <input type="text" name="kabupaten" value="<?php echo e(old('kabupaten', $calonSantri->kabupaten)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                    <input type="text" name="kecamatan" value="<?php echo e(old('kecamatan', $calonSantri->kecamatan)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Desa</label>
                                    <input type="text" name="desa" value="<?php echo e(old('desa', $calonSantri->desa)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="kode_pos" value="<?php echo e(old('kode_pos', $calonSantri->kode_pos)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                                    <input type="text" name="no_telp" value="<?php echo e(old('no_telp', $calonSantri->no_telp)); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Keluarga -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨‍👩‍👧‍👦 Data Keluarga</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Kartu Keluarga</label>
                                    <input type="text" name="no_kk" value="<?php echo e(old('no_kk', $calonSantri->no_kk)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Pendapatan Keluarga</label>
                                    <select name="pendapatan_keluarga" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\PendapatanKeluarga::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendapatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pendapatan->nama); ?>" <?php echo e(old('pendapatan_keluarga', $calonSantri->pendapatan_keluarga) === $pendapatan->nama ? 'selected' : ''); ?>><?php echo e($pendapatan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Saudara</label>
                                    <input type="number" name="jumlah_saudara" value="<?php echo e(old('jumlah_saudara', $calonSantri->jumlah_saudara)); ?>" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Ayah -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨 Data Ayah</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah *</label>
                                    <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah', $calonSantri->nama_ayah)); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ayah" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ayah</label>
                                    <input type="text" name="nik_ayah" value="<?php echo e(old('nik_ayah', $calonSantri->nik_ayah)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ayah</label>
                                    <select name="pendidikan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\Pendidikan::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendidikan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pendidikan->nama); ?>" <?php echo e(old('pendidikan_ayah', $calonSantri->pendidikan_ayah) === $pendidikan->nama ? 'selected' : ''); ?>><?php echo e($pendidikan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah</label>
                                    <select name="pekerjaan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\Pekerjaan::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pekerjaan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pekerjaan->nama); ?>" <?php echo e(old('pekerjaan_ayah', $calonSantri->pekerjaan_ayah) === $pekerjaan->nama ? 'selected' : ''); ?>><?php echo e($pekerjaan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HP Ayah</label>
                                    <input type="text" name="hp_ayah" value="<?php echo e(old('hp_ayah', $calonSantri->hp_ayah)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Ibu -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👩 Data Ibu</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu *</label>
                                    <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu', $calonSantri->nama_ibu)); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ibu" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ibu</label>
                                    <input type="text" name="nik_ibu" value="<?php echo e(old('nik_ibu', $calonSantri->nik_ibu)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
                                    <select name="pendidikan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\Pendidikan::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendidikan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pendidikan->nama); ?>" <?php echo e(old('pendidikan_ibu', $calonSantri->pendidikan_ibu) === $pendidikan->nama ? 'selected' : ''); ?>><?php echo e($pendidikan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                                    <select name="pekerjaan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\Pekerjaan::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pekerjaan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pekerjaan->nama); ?>" <?php echo e(old('pekerjaan_ibu', $calonSantri->pekerjaan_ibu) === $pekerjaan->nama ? 'selected' : ''); ?>><?php echo e($pekerjaan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HP Ibu</label>
                                    <input type="text" name="hp_ibu" value="<?php echo e(old('hp_ibu', $calonSantri->hp_ibu)); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
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
                                        <option value="baru" <?php echo e(old('status', $calonSantri->status) === 'baru' ? 'selected' : ''); ?>>Baru</option>
                                        <option value="proses" <?php echo e(old('status', $calonSantri->status) === 'proses' ? 'selected' : ''); ?>>Proses</option>
                                        <option value="lolos" <?php echo e(old('status', $calonSantri->status) === 'lolos' ? 'selected' : ''); ?>>Lolos</option>
                                        <option value="tidak_lolos" <?php echo e(old('status', $calonSantri->status) === 'tidak_lolos' ? 'selected' : ''); ?>>Tidak Lolos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Catatan tambahan (opsional)"><?php echo e(old('catatan', $calonSantri->catatan)); ?></textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-6 border-t">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                                ✅ Simpan Perubahan
                            </button>
                            <a href="<?php echo e(route('admin.calon-santri.index', ['jenjang' => $calonSantri->jenjang])); ?>" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Rhezal Maulana\Documents\GitHub\PSB_SAZA\resources\views/admin/calon-santri/edit.blade.php ENDPATH**/ ?>