

<?php $__env->startSection('title', 'Upload Dokumen'); ?>
<?php $__env->startSection('page-title', '📤 Upload Dokumen'); ?>

<?php $__env->startSection('page-subtitle'); ?>
    <p class="text-sm text-gray-600 mt-1">Santri: <span class="font-bold text-blue-600"><?php echo e($calonSantri->nama); ?></span></p>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-white rounded-lg shadow max-w-4xl">
        <div class="p-8">
            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    ✅ <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if(session('error')): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    ❌ <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
                <p class="text-sm"><strong>ℹ️ Info:</strong> Upload semua dokumen wajib. Gambar akan otomatis dikompres hingga 2MB.</p>
            </div>

            <!-- Upload Grid -->
            <div class="grid grid-cols-3 gap-6">
                <?php $__currentLoopData = $dokumenTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $dokumen = $calonSantri->dokumens()->where('tipe_dokumen', $value)->first();
                    ?>
                    
                    <div class="border border-gray-300 rounded-lg p-4">
                        <h3 class="text-sm font-bold text-gray-800"><?php echo e($label); ?></h3>
                        
                        <?php if($dokumen): ?>
                            <!-- Preview Document -->
                            <div class="bg-gray-100 rounded border border-gray-300 p-2 my-3 h-40 flex items-center justify-center overflow-hidden">
                                <?php
                                    $ext = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                    $fileExists = \Storage::disk('public')->exists($dokumen->file_path);
                                ?>
                                <?php if($fileExists && $isImage): ?>
                                    <img src="<?php echo e(asset('storage/' . $dokumen->file_path)); ?>" alt="<?php echo e($label); ?>" class="max-w-full max-h-full object-contain">
                                <?php elseif($fileExists): ?>
                                    <div class="text-center">
                                        <p class="text-2xl">📄</p>
                                        <p class="text-xs text-gray-600 mt-1"><?php echo e(strtoupper($ext)); ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <p class="text-red-500 text-lg">⚠️</p>
                                        <p class="text-xs text-red-600 mt-1">File tidak ditemukan</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-green-600 text-center mb-2">✅ <?php echo e($fileExists ? 'Sudah diupload' : 'Sudah diupload (File hilang)'); ?></p>
                            <p class="text-xs text-gray-500 text-center mb-3"><?php echo e($dokumen->created_at->format('d/m/Y H:i')); ?></p>
                            
                            <!-- Upload Ulang Form - Hidden -->
                            <form action="<?php echo e(route('dokumen.store', $calonSantri)); ?>" method="POST" enctype="multipart/form-data" class="form-upload hidden" id="form-ulang-<?php echo e($loop->index); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="tipe_dokumen" value="<?php echo e($value); ?>">
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-ulang-<?php echo e($loop->index); ?>">
                            </form>

                            <button type="button" class="w-full text-white px-3 py-2 rounded hover:shadow-lg font-semibold transition text-sm" style="background-color: #f0b43c; color: #333;" onclick="showUploadMenu('ulang-<?php echo e($loop->index); ?>', '<?php echo e($value); ?>')">
                                🔄 Upload Ulang
                            </button>
                        <?php else: ?>
                            <!-- Upload Form - Hidden -->
                            <form action="<?php echo e(route('dokumen.store', $calonSantri)); ?>" method="POST" enctype="multipart/form-data" class="form-upload hidden" id="form-<?php echo e($loop->index); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="tipe_dokumen" value="<?php echo e($value); ?>">
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-<?php echo e($loop->index); ?>">
                            </form>

                            <!-- Upload Button -->
                            <button type="button" class="w-full text-white px-3 py-2 rounded hover:shadow-lg font-semibold transition text-sm" style="background-color: #00a0a0;" onclick="showUploadMenu('<?php echo e($loop->index); ?>', '<?php echo e($value); ?>')">
                                📤 Upload <?php echo e($label); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Optional Documents -->
            <div class="mt-8 pt-6 border-t">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🎖️ Dokumen Opsional</h3>
                
                <?php
                    $optionalDocs = $calonSantri->dokumens()->whereNotIn('tipe_dokumen', array_keys($dokumenTypes))->get();
                ?>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                    <?php $__currentLoopData = $optionalDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-green-300 rounded-lg p-4 bg-green-50">
                            <h3 class="text-sm font-bold text-gray-800"><?php echo e($doc->tipe_dokumen); ?></h3>
                            
                            <div class="bg-gray-100 rounded border border-gray-300 p-2 my-3 h-40 flex items-center justify-center overflow-hidden">
                                <?php
                                    $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                    $fileExists = \Storage::disk('public')->exists($doc->file_path);
                                ?>
                                <?php if($fileExists && $isImage): ?>
                                    <img src="<?php echo e(asset('storage/' . $doc->file_path)); ?>" alt="<?php echo e($doc->tipe_dokumen); ?>" class="max-w-full max-h-full object-contain">
                                <?php elseif($fileExists): ?>
                                    <div class="text-center">
                                        <p class="text-2xl">📄</p>
                                        <p class="text-xs text-gray-600 mt-1"><?php echo e(strtoupper($ext)); ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <p class="text-red-500 text-lg">⚠️</p>
                                        <p class="text-xs text-red-600 mt-1">File tidak ditemukan</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-green-600 text-center mb-2">✅ Diupload</p>
                            <p class="text-xs text-gray-500 text-center mb-3"><?php echo e($doc->created_at->format('d/m/Y H:i')); ?></p>
                            
                            <!-- Delete & Upload Ulang -->
                            <form action="<?php echo e(route('dokumen.destroy', $doc)); ?>" method="POST" class="inline-block w-full">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-full bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 font-semibold transition text-xs" onclick="return confirm('Hapus dokumen ini?')">🗑️ Hapus</button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Tambah Dokumen Opsional -->
                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 flex flex-col items-center justify-center bg-blue-50 hover:bg-blue-100 transition cursor-pointer" onclick="document.getElementById('addOptionalForm').classList.toggle('hidden')">
                        <p class="text-3xl">➕</p>
                        <p class="text-xs font-semibold text-gray-700 mt-2">Tambah Dokumen</p>
                    </div>
                </div>

                <!-- Add Optional Document Form -->
                <div id="addOptionalForm" class="hidden border border-blue-300 rounded-lg p-6 bg-blue-50">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Tambah Dokumen Opsional</h3>
                    
                    <form action="<?php echo e(route('dokumen.store', $calonSantri)); ?>" method="POST" enctype="multipart/form-data" class="form-upload space-y-4" id="form-optional">
                        <?php echo csrf_field(); ?>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Dokumen *</label>
                            <input type="text" name="tipe_dokumen" placeholder="Contoh: Piagam, Sertifikat, Medali, dll" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-optional">

                        <div class="flex gap-3">
                            <button type="button" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold transition" onclick="showUploadMenuOptional()">📤 Upload File</button>
                            <button type="button" onclick="document.getElementById('addOptionalForm').classList.add('hidden')" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 font-semibold transition">❌ Batal</button>
                        </div>
                    </form>
                </div>
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

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t mt-6">
                <a href="<?php echo e(route('admin.calon-santri.show', $calonSantri)); ?>" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">✅ Selesai</a>
            </div>
        </div>
    </div>

    <script>
        let uploadMenu = null;
        let selectedFormId = null;

        function showUploadMenu(formIndex, tipeDokumen) {
            selectedFormId = 'form-' + formIndex;
            uploadMenu = document.createElement('div');
            uploadMenu.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            uploadMenu.innerHTML = `
                <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">📤 Upload ${tipeDokumen}</h3>
                    <div class="flex flex-col gap-3">
                        <button type="button" class="flex items-center justify-center gap-3 bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 font-semibold transition" onclick="chooseFile()">📁 Pilih File</button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 font-semibold transition" onclick="openCameraFromMenu()">📷 Ambil Foto</button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-gray-400 text-white px-6 py-3 rounded hover:bg-gray-500 font-semibold transition" onclick="closeUploadMenu()">❌ Batal</button>
                    </div>
                </div>
            `;
            document.body.appendChild(uploadMenu);
        }

        function showUploadMenuOptional() {
            selectedFormId = 'form-optional';
            const tipeDokumenInput = document.querySelector('#form-optional input[name="tipe_dokumen"]');
            if (!tipeDokumenInput.value) {
                alert('Isi nama dokumen terlebih dahulu!');
                return;
            }
            uploadMenu = document.createElement('div');
            uploadMenu.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            uploadMenu.innerHTML = `
                <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4">
                    <h3 class="text-xl font-bold mb-6 text-gray-800">📤 Upload Dokumen</h3>
                    <div class="flex flex-col gap-3">
                        <button type="button" class="flex items-center justify-center gap-3 bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 font-semibold transition" onclick="chooseFile()">📁 Pilih File</button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 font-semibold transition" onclick="openCameraFromMenu()">📷 Ambil Foto</button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-gray-400 text-white px-6 py-3 rounded hover:bg-gray-500 font-semibold transition" onclick="closeUploadMenu()">❌ Batal</button>
                    </div>
                </div>
            `;
            document.body.appendChild(uploadMenu);
        }

        function closeUploadMenu() {
            if (uploadMenu) {
                uploadMenu.remove();
                uploadMenu = null;
            }
        }

        function chooseFile() {
            closeUploadMenu();
            const form = document.getElementById(selectedFormId);
            const fileInput = form.querySelector('input[type="file"]');
            fileInput.click();
            fileInput.addEventListener('change', submitFormOnFileChange);
        }

        function submitFormOnFileChange(e) {
            const form = document.getElementById(selectedFormId);
            if (form.querySelector('input[type="file"]').files.length > 0) {
                setTimeout(() => {
                    form.submit();
                }, 100);
            }
            e.target.removeEventListener('change', submitFormOnFileChange);
        }

        function openCameraFromMenu() {
            const form = document.getElementById(selectedFormId);
            const fileInput = form.querySelector('input[type="file"]');
            closeUploadMenu();
            openCamera(fileInput.id);
        }

        document.addEventListener('change', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'file' && e.target.files[0]) {
                const form = e.target.closest('.form-upload');
                if (form && !form.classList.contains('hidden')) {
                    form.submit();
                }
            }
        });

        let cameraStream = null;
        let cameraModal = null;

        function openCamera(inputId) {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('📷 Kamera tidak tersedia di device ini. Gunakan file upload.');
                return;
            }
            cameraModal = document.createElement('div');
            cameraModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            cameraModal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-bold mb-4">📷 Ambil Foto</h3>
                    <video id="cameraVideo" class="w-full rounded border-2 border-gray-300 mb-4" style="max-height: 400px; object-fit: cover;"></video>
                    <canvas id="cameraCanvas" class="hidden"></canvas>
                    <div class="flex gap-2">
                        <button type="button" onclick="capturePhoto('${inputId}')" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">📸 Ambil Foto</button>
                        <button type="button" onclick="closeCamera()" class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">❌ Batal</button>
                    </div>
                    <p class="text-xs text-gray-500 mt-3 text-center">Posisikan objek dengan jelas di depan kamera</p>
                </div>
            `;
            document.body.appendChild(cameraModal);
            const video = document.getElementById('cameraVideo');
            navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } },
                audio: false
            }).then(stream => {
                cameraStream = stream;
                video.srcObject = stream;
                video.play();
            }).catch(err => {
                alert('❌ Gagal akses kamera: ' + err.message);
                closeCamera();
            });
        }

        function capturePhoto(inputId) {
            const video = document.getElementById('cameraVideo');
            const canvas = document.getElementById('cameraCanvas');
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            canvas.toBlob(blob => {
                const file = new File([blob], 'camera-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                const fileInput = document.getElementById(inputId);
                fileInput.files = dataTransfer.files;
                fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                closeCamera();
                console.log('📸 Foto berhasil ditangkap: ' + file.name);
            }, 'image/jpeg', 0.9);
        }

        function closeCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            if (cameraModal) {
                cameraModal.remove();
                cameraModal = null;
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rhezal Maulana\Documents\GitHub\PSB_SAZA\resources\views/admin/dokumen/create.blade.php ENDPATH**/ ?>