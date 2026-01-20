

<?php $__env->startSection('title', 'Tambah Calon Santri'); ?>
<?php $__env->startSection('page-title', 'Tambah Calon Santri'); ?>

<?php $__env->startSection('page-subtitle'); ?>
    <p class="text-sm text-gray-600 mt-1">Tambah data calon santri baru ke sistem</p>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>            <div class="bg-white shadow p-6 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Tambah Calon Santri Baru</h2>
                    <p class="text-sm text-gray-600 mt-1">Jenjang: <span class="font-bold text-indigo-600"><?php echo e($jenjang); ?></span></p>
                </div>
                <a href="<?php echo e(route('admin.calon-santri.index', ['jenjang' => $jenjang])); ?>" class="text-gray-600 hover:text-gray-800 font-semibold">
                    ← Kembali
                </a>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="bg-white rounded-lg shadow max-w-6xl">
                    <form method="POST" action="<?php echo e(route('admin.calon-santri.store')); ?>" class="p-8 space-y-8">
                        <?php echo csrf_field(); ?>

                        <!-- Hidden Jenjang Field -->
                        <input type="hidden" name="jenjang" value="<?php echo e($jenjang); ?>" />
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

                        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4">
                            <p class="text-sm"><strong>ℹ️ Info:</strong> No. Pendaftaran akan di-generate otomatis dengan format PSB-TAHUN-NOMOR</p>
                        </div>

                        <!-- Section: Data Santri -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">📋 Data Santri</h3>
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                    <input type="text" name="nisn" value="<?php echo e(old('nisn')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Santri</label>
                                    <input type="text" name="nik_santri" value="<?php echo e(old('nik_santri')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Santri *</label>
                                    <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama lengkap" />
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="laki-laki" <?php echo e(old('jenis_kelamin') === 'laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="perempuan" <?php echo e(old('jenis_kelamin') === 'perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Kota/Kabupaten" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah *</label>
                                    <input type="text" name="asal_sekolah" value="<?php echo e(old('asal_sekolah')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="<?php echo e($jenjang === 'MTs' ? 'SD/MI ...' : 'SMP/MTs ...'); ?>" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Hobi</label>
                                    <input type="text" name="hobi" value="<?php echo e(old('hobi')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cita-cita</label>
                                    <input type="text" name="cita_cita" value="<?php echo e(old('cita_cita')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Alamat Lengkap -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">🏠 Alamat Lengkap</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                                <textarea name="alamat" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan alamat lengkap"><?php echo e(old('alamat')); ?></textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <input type="text" name="provinsi" value="<?php echo e(old('provinsi')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten</label>
                                    <input type="text" name="kabupaten" value="<?php echo e(old('kabupaten')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                    <input type="text" name="kecamatan" value="<?php echo e(old('kecamatan')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Desa</label>
                                    <input type="text" name="desa" value="<?php echo e(old('desa')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="kode_pos" value="<?php echo e(old('kode_pos')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                                    <input type="text" name="no_telp" value="<?php echo e(old('no_telp')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Keluarga -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨‍👩‍👧‍👦 Data Keluarga</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Kartu Keluarga</label>
                                    <input type="text" name="no_kk" value="<?php echo e(old('no_kk')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rata-rata Pendapatan Keluarga</label>
                                    <select name="pendapatan_keluarga" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\PendapatanKeluarga::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendapatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pendapatan->nama); ?>" <?php echo e(old('pendapatan_keluarga') === $pendapatan->nama ? 'selected' : ''); ?>><?php echo e($pendapatan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Saudara</label>
                                    <input type="number" name="jumlah_saudara" value="<?php echo e(old('jumlah_saudara')); ?>" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Ayah -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👨 Data Ayah</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah *</label>
                                    <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ayah" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ayah</label>
                                    <input type="text" name="nik_ayah" value="<?php echo e(old('nik_ayah')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ayah</label>
                                    <select name="pendidikan_ayah" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\Pendidikan::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendidikan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pendidikan->nama); ?>" <?php echo e(old('pendidikan_ayah') === $pendidikan->nama ? 'selected' : ''); ?>><?php echo e($pendidikan->nama); ?></option>
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
                                            <option value="<?php echo e($pekerjaan->nama); ?>" <?php echo e(old('pekerjaan_ayah') === $pekerjaan->nama ? 'selected' : ''); ?>><?php echo e($pekerjaan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HP Ayah</label>
                                    <input type="text" name="hp_ayah" value="<?php echo e(old('hp_ayah')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
                                </div>
                            </div>
                        </div>

                        <!-- Section: Data Ibu -->
                        <div>
                            <h3 class="text-xl font-bold text-indigo-600 mb-6 pb-2 border-b-2 border-indigo-600">👩 Data Ibu</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu *</label>
                                    <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu')); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nama lengkap ibu" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK Ibu</label>
                                    <input type="text" name="nik_ibu" value="<?php echo e(old('nik_ibu')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opsional" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
                                    <select name="pendidikan_ibu" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Pilih --</option>
                                        <?php $__currentLoopData = \App\Models\Pendidikan::orderBy('nama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendidikan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pendidikan->nama); ?>" <?php echo e(old('pendidikan_ibu') === $pendidikan->nama ? 'selected' : ''); ?>><?php echo e($pendidikan->nama); ?></option>
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
                                            <option value="<?php echo e($pekerjaan->nama); ?>" <?php echo e(old('pekerjaan_ibu') === $pekerjaan->nama ? 'selected' : ''); ?>><?php echo e($pekerjaan->nama); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">HP Ibu</label>
                                    <input type="text" name="hp_ibu" value="<?php echo e(old('hp_ibu')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08xxxxxxxxxx" />
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
                                        <option value="baru" <?php echo e(old('status') === 'baru' ? 'selected' : ''); ?>>Baru</option>
                                        <option value="proses" <?php echo e(old('status') === 'proses' ? 'selected' : ''); ?>>Proses</option>
                                        <option value="lolos" <?php echo e(old('status') === 'lolos' ? 'selected' : ''); ?>>Lolos</option>
                                        <option value="tidak_lolos" <?php echo e(old('status') === 'tidak_lolos' ? 'selected' : ''); ?>>Tidak Lolos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Catatan tambahan (opsional)"><?php echo e(old('catatan')); ?></textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-6 border-t">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                                ✅ Simpan
                            </button>
                            <a href="<?php echo e(route('admin.calon-santri.index')); ?>" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
<?php $__env->stopSection(); ?>
                    
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rhezal Maulana\Documents\GitHub\PSB_SAZA\resources\views/admin/calon-santri/create.blade.php ENDPATH**/ ?>