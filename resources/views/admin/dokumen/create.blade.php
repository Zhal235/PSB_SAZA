@extends('layouts.admin')

@section('title', 'Upload Dokumen')
@section('page-title', '📤 Upload Dokumen')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Santri: <span class="font-bold text-blue-600">{{ $calonSantri->nama }}</span></p>
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
            <div class="grid grid-cols-3 gap-6">
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
                                        <p class="text-red-500 text-lg">⚠️</p>
                                        <p class="text-xs text-red-600 mt-1">File tidak ditemukan</p>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-green-600 text-center mb-2">✅ {{ $fileExists ? 'Sudah diupload' : 'Sudah diupload (File hilang)' }}</p>
                            <p class="text-xs text-gray-500 text-center mb-3">{{ $dokumen->created_at->format('d/m/Y H:i') }}</p>
                            
                            <!-- Upload Ulang Form - Hidden -->
                            <form action="{{ route('admin.dokumen.store', $calonSantri) }}" method="POST" enctype="multipart/form-data" class="form-upload hidden" id="form-ulang-{{ $loop->index }}">
                                @csrf
                                <input type="hidden" name="tipe_dokumen" value="{{ $value }}">
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-ulang-{{ $loop->index }}">
                            </form>

                            <button type="button" class="w-full text-white px-3 py-2 rounded hover:shadow-lg font-semibold transition text-sm" style="background-color: #f0b43c; color: #333;" onclick="showUploadMenu('ulang-{{ $loop->index }}', '{{ $value }}')">
                                🔄 Upload Ulang
                            </button>
                        @else
                            <!-- Upload Form - Hidden -->
                            <form action="{{ route('admin.dokumen.store', $calonSantri) }}" method="POST" enctype="multipart/form-data" class="form-upload hidden" id="form-{{ $loop->index }}">
                                @csrf
                                <input type="hidden" name="tipe_dokumen" value="{{ $value }}">
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-{{ $loop->index }}">
                            </form>

                            <!-- Upload Button -->
                            <button type="button" class="w-full text-white px-3 py-2 rounded hover:shadow-lg font-semibold transition text-sm" style="background-color: #00a0a0;" onclick="showUploadMenu('{{ $loop->index }}', '{{ $value }}')">
                                📤 Upload {{ $label }}
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Optional Documents -->
            <div class="mt-8 pt-6 border-t">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🎖️ Dokumen Opsional</h3>
                
                @php
                    $optionalDocs = $calonSantri->dokumens()->whereNotIn('tipe_dokumen', array_keys($dokumenTypes))->get();
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                    @foreach($optionalDocs as $doc)
                        <div class="border border-green-300 rounded-lg p-4 bg-green-50">
                            <h3 class="text-sm font-bold text-gray-800">{{ $doc->tipe_dokumen }}</h3>
                            
                            <div class="bg-gray-100 rounded border border-gray-300 p-2 my-3 h-40 flex items-center justify-center overflow-hidden">
                                @php
                                    $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                    $fileExists = \Storage::disk('public')->exists($doc->file_path);
                                @endphp
                                @if($fileExists && $isImage)
                                    <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->tipe_dokumen }}" class="max-w-full max-h-full object-contain">
                                @elseif($fileExists)
                                    <div class="text-center">
                                        <p class="text-2xl">📄</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ strtoupper($ext) }}</p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <p class="text-red-500 text-lg">⚠️</p>
                                        <p class="text-xs text-red-600 mt-1">File tidak ditemukan</p>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-green-600 text-center mb-2">✅ Diupload</p>
                            <p class="text-xs text-gray-500 text-center mb-3">{{ $doc->created_at->format('d/m/Y H:i') }}</p>
                            
                            <!-- Delete & Upload Ulang -->
                            <form action="{{ route('admin.dokumen.destroy', $doc) }}" method="POST" class="inline-block w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 font-semibold transition text-xs" onclick="return confirm('Hapus dokumen ini?')">🗑️ Hapus</button>
                            </form>
                        </div>
                    @endforeach

                    <!-- Tambah Dokumen Opsional -->
                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 flex flex-col items-center justify-center bg-blue-50 hover:bg-blue-100 transition cursor-pointer" onclick="document.getElementById('addOptionalForm').classList.toggle('hidden')">
                        <p class="text-3xl">➕</p>
                        <p class="text-xs font-semibold text-gray-700 mt-2">Tambah Dokumen</p>
                    </div>
                </div>

                <!-- Add Optional Document Form -->
                <div id="addOptionalForm" class="hidden border border-blue-300 rounded-lg p-6 bg-blue-50">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">➕ Tambah Dokumen Opsional</h3>
                    
                    <form action="{{ route('admin.dokumen.store', $calonSantri) }}" method="POST" enctype="multipart/form-data" class="form-upload space-y-4" id="form-optional">
                        @csrf
                        
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
                <a href="{{ route('admin.calon-santri.show', $calonSantri) }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">✅ Selesai</a>
            </div>
        </div>
    </div>

    <script>
        let uploadMenu = null;
        let selectedFormId = null;

        function showUploadMenu(formIndex, tipeDokumen) {
            // formIndex bisa berupa '0' atau 'ulang-0', jadi pastikan ada prefix 'form-'
            selectedFormId = formIndex.startsWith('form-') ? formIndex : 'form-' + formIndex;
            console.log('📝 showUploadMenu called - formIndex:', formIndex, 'selectedFormId:', selectedFormId);
            
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

            console.log('🎥 capturePhoto called - inputId:', inputId);
            console.log('📹 Video:', video ? video.videoWidth + 'x' + video.videoHeight : 'NOT FOUND');

            if (!video.videoWidth || !video.videoHeight) {
                console.error('❌ Video belum siap');
                alert('⏳ Tunggu sebentar, kamera masih loading...');
                return;
            }

            try {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                console.log('📐 Canvas size:', canvas.width + 'x' + canvas.height);
                
                ctx.drawImage(video, 0, 0);
                console.log('✅ Frame drawn');

                // Pre-resize untuk optimize
                const maxSize = 800;
                if (canvas.width > maxSize || canvas.height > maxSize) {
                    console.log('📉 Resizing...');
                    const ratio = Math.min(maxSize / canvas.width, maxSize / canvas.height);
                    const newWidth = Math.floor(canvas.width * ratio);
                    const newHeight = Math.floor(canvas.height * ratio);
                    
                    const resizedCanvas = document.createElement('canvas');
                    resizedCanvas.width = newWidth;
                    resizedCanvas.height = newHeight;
                    const resizedCtx = resizedCanvas.getContext('2d');
                    resizedCtx.drawImage(canvas, 0, 0, newWidth, newHeight);
                    
                    console.log('📐 Resized to:', newWidth + 'x' + newHeight);
                    console.log('⏳ Converting to blob...');
                    
                    resizedCanvas.toBlob(blob => {
                        console.log('🔔 Blob callback triggered!', blob ? blob.size + ' bytes' : 'NULL');
                        handleCapturedBlob(blob, inputId);
                    }, 'image/jpeg', 0.6);
                } else {
                    console.log('⏳ Converting to blob...');
                    canvas.toBlob(blob => {
                        console.log('🔔 Blob callback triggered!', blob ? blob.size + ' bytes' : 'NULL');
                        handleCapturedBlob(blob, inputId);
                    }, 'image/jpeg', 0.6);
                }
            } catch (e) {
                console.error('❌ Capture error:', e);
                alert('❌ Error: ' + e.message);
            }
        }

        function handleCapturedBlob(blob, inputId) {
            if (!blob) {
                console.error('❌ Blob is null!');
                alert('❌ Gagal capture foto');
                return;
            }

            console.log('✅ Processing blob:', blob.size, 'bytes');
            closeCamera();

            try {
                const form = document.getElementById(selectedFormId);
                if (!form) {
                    console.error('❌ Form tidak ditemukan:', selectedFormId);
                    alert('❌ Error: Form tidak ditemukan');
                    return;
                }

                console.log('✅ Form found:', form.id);

                // Show loading
                const loadingModal = document.createElement('div');
                loadingModal.id = 'uploadLoadingModal';
                loadingModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                loadingModal.innerHTML = `
                    <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4 text-center">
                        <p class="text-lg font-bold mb-4">⏳ Uploading...</p>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full animate-pulse" style="width: 100%;"></div>
                        </div>
                    </div>
                `;
                document.body.appendChild(loadingModal);

                // Create FormData
                const formData = new FormData();
                const fileName = 'camera-' + Date.now() + '.jpg';
                formData.append('file', blob, fileName);
                formData.append('tipe_dokumen', form.querySelector('input[name="tipe_dokumen"]').value);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value);

                console.log('📤 Uploading to:', form.action);

                // Upload via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    console.log('✅ Response status:', response.status);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Upload success!', data);
                    const modal = document.getElementById('uploadLoadingModal');
                    if (modal) modal.remove();
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error('❌ Upload error:', error);
                    const modal = document.getElementById('uploadLoadingModal');
                    if (modal) modal.remove();
                    alert('❌ Upload gagal: ' + error.message);
                });
            } catch (e) {
                console.error('❌ Error:', e);
                alert('❌ Error: ' + e.message);
            }
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
@endsection
