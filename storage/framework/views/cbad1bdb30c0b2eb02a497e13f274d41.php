<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon Santri - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php echo $__env->make('components.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white shadow-xl p-6 flex justify-between items-center" style="border-bottom: 4px solid #00a0a0;">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #007a7a;">Kelola Calon Santri</h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola data calon santri yang mendaftar</p>
                </div>
                <a href="<?php echo e(route('admin.calon-santri.create')); ?>" class="text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all duration-300 font-semibold" style="background-color: #00a0a0;">
                    ➕ Tambah Calon Santri
                </a>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Tabs untuk Jenjang -->
                    <div class="bg-white rounded-lg shadow-md mb-6 border-b">
                        <div class="flex space-x-2 px-6">
                            <a href="<?php echo e(route('admin.calon-santri.index', ['jenjang' => 'MTs'])); ?>" 
                                class="px-6 py-4 font-semibold border-b-2 transition
                                <?php echo e($jenjang === 'MTs' ? 'border-[#00a0a0] text-[#00a0a0]' : 'border-transparent text-gray-600 hover:text-gray-800'); ?>">
                                🏫 MTs (<?php echo e(\App\Models\CalonSantri::where('jenjang', 'MTs')->count()); ?>)
                            </a>
                            <a href="<?php echo e(route('admin.calon-santri.index', ['jenjang' => 'SMK'])); ?>" 
                                class="px-6 py-4 font-semibold border-b-2 transition
                                <?php echo e($jenjang === 'SMK' ? 'border-[#00a0a0] text-[#00a0a0]' : 'border-transparent text-gray-600 hover:text-gray-800'); ?>">
                                🎓 SMK (<?php echo e(\App\Models\CalonSantri::where('jenjang', 'SMK')->count()); ?>)
                            </a>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <?php if(session('success')): ?>
                        <div class="bg-gradient-to-r from-[#E8F5E9] to-[#C8E6C9] border-l-4 text-[#1B5E20] p-4 mb-6 rounded-lg shadow-md" style="border-left-color: #00a0a0;">
                            ✅ <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Table -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <?php if($calonSantri->count() > 0): ?>
                            <table class="w-full text-left">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">No. Daftar</th>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Nama</th>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Jenjang</th>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">No. Telp</th>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Asal Sekolah</th>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <?php $__currentLoopData = $calonSantri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-3 font-mono font-semibold text-sm"><?php echo e($santri->no_pendaftaran); ?></td>
                                            <td class="px-6 py-3">
                                                <div>
                                                    <p class="font-semibold text-gray-800"><?php echo e($santri->nama); ?></p>
                                                    <?php if($santri->user): ?>
                                                        <p class="text-xs text-gray-500 mt-1">✓ Akun Terkait</p>
                                                    <?php else: ?>
                                                        <p class="text-xs text-yellow-600 mt-1">⚠ Belum Ada Akun</p>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3">
                                                <?php if($santri->jenjang === 'MTs'): ?>
                                                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                        🏫 MTs
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                        🎓 SMK
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-3 font-mono text-sm"><?php echo e($santri->no_telp); ?></td>
                                            <td class="px-6 py-3 text-sm"><?php echo e(Str::limit($santri->asal_sekolah, 20)); ?></td>
                                            <td class="px-6 py-3">
                                                <?php if($santri->status === 'lolos'): ?>
                                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">✅ Lolos</span>
                                                <?php elseif($santri->status === 'tidak_lolos'): ?>
                                                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-semibold">❌ Tidak Lolos</span>
                                                <?php elseif($santri->status === 'proses'): ?>
                                                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">⏳ Proses</span>
                                                <?php else: ?>
                                                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded text-xs font-semibold">🆕 Baru</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-3">
                                                <div class="flex gap-2">
                                                    <a href="<?php echo e(route('admin.calon-santri.show', $santri)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                                        👁️
                                                    </a>
                                                    <a href="<?php echo e(route('admin.calon-santri.edit', $santri)); ?>" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                                        ✏️
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <div class="p-6 bg-gray-50 border-t">
                                <?php echo e($calonSantri->links()); ?>

                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center text-gray-500">
                                <p>Belum ada calon santri di jenjang <?php echo e($jenjang); ?></p>
                                <a href="<?php echo e(route('admin.calon-santri.create', ['jenjang' => $jenjang])); ?>" class="text-[#00a0a0] hover:underline font-semibold">
                                    Tambah sekarang
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Rhezal Maulana\Documents\GitHub\PSB_SAZA\resources\views/admin/calon-santri/index.blade.php ENDPATH**/ ?>