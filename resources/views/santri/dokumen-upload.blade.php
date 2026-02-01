@extends('layouts.santri')

@section('title', 'Upload Dokumen')
@section('page-title', '📤 Upload Dokumen')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Santri: <span class="font-bold text-blue-600">{{ $calonSantri->nama }}</span></p>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 lg:p-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 lg:p-4 mb-4 lg:mb-6 text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 lg:p-4 mb-4 lg:mb-6 text-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 lg:p-4 mb-4 lg:mb-6">
                <p class="text-xs lg:text-sm"><strong>ℹ️ Info:</strong> Upload semua dokumen wajib. Gambar akan otomatis dikompres hingga 2MB.</p>
            </div>

            <!-- Hardcopy Reminder Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-3 lg:p-4 mb-4 lg:mb-6">
                <p class="text-xs lg:text-sm font-semibold mb-2">📬 Jangan Lupa Siapkan Hardcopy!</p>
                <p class="text-xs mb-3">Selain upload digital, Anda juga perlu menyiapkan hardcopy (fotokopi) dokumen untuk diserahkan ke sekretariat:</p>
                <ul class="text-xs ml-4 space-y-1 list-disc">
                    <li><strong>5 lembar</strong> fotokopi untuk setiap jenis dokumen</li>
                    <li>Fotokopi harus jelas, rapi, dan mudah dibaca</li>
                    <li>Bisa diantar langsung atau dikirim ke sekretariat</li>
                    <li>Perhatikan jadwal penyerahan yang diumumkan</li>
                </ul>
            </div>

            <!-- Upload Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($dokumenTypes as $value => $label)
                    @php
                        $dokumen = $calonSantri->dokumens()->where('tipe_dokumen', $value)->first();
                    @endphp
                    
                    <div class="border border-gray-300 rounded-lg p-3 lg:p-4">
                        <h3 class="text-xs lg:text-sm font-bold text-gray-800 mb-3">{{ $label }}</h3>
                        
                        @if($dokumen)
                            <!-- Preview Document -->
                            <div class="bg-gray-100 rounded border border-gray-300 p-2 my-3 h-32 lg:h-40 flex items-center justify-center overflow-hidden">
                                @php
                                    $ext = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                    $fileExists = \Storage::disk('public')->exists($dokumen->file_path);
                                @endphp
                                @if($fileExists && $isImage)
                                    <img src="{{ asset('storage/' . $dokumen->file_path) }}" alt="{{ $label }}" class="max-w-full max-h-full object-contain">
                                @elseif($fileExists)
                                    <div class="text-center">
                                        <p class="text-xl lg:text-2xl">📄</p>
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
                            <form action="{{ route('santri.dokumen-store') }}" method="POST" enctype="multipart/form-data" class="form-upload hidden" id="form-ulang-{{ $loop->index }}">
                                @csrf
                                <input type="hidden" name="tipe_dokumen" value="{{ $value }}">
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-ulang-{{ $loop->index }}">
                            </form>

                            <button type="button" class="w-full text-white px-3 py-3 rounded hover:shadow-lg font-semibold transition text-xs lg:text-sm touch-manipulation" style="background-color: #f0b43c; color: #333;" onclick="showUploadMenu('ulang-{{ $loop->index }}', '{{ $value }}')">
                                🔄 Upload Ulang
                            </button>
                        @else
                            <!-- Placeholder untuk preview -->
                            <div class="bg-gray-100 rounded border border-gray-300 p-2 my-3 h-32 lg:h-40 flex items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <p class="text-3xl lg:text-4xl mb-2">📤</p>
                                    <p class="text-xs">Belum diupload</p>
                                </div>
                            </div>

                            <!-- Upload Form - Hidden -->
                            <form action="{{ route('santri.dokumen-store') }}" method="POST" enctype="multipart/form-data" class="form-upload hidden" id="form-{{ $loop->index }}">
                                @csrf
                                <input type="hidden" name="tipe_dokumen" value="{{ $value }}">
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-{{ $loop->index }}">
                            </form>

                            <!-- Upload Button -->
                            <button type="button" class="w-full text-white px-3 py-3 rounded hover:shadow-lg font-semibold transition text-xs lg:text-sm touch-manipulation" style="background-color: #00a0a0;" onclick="showUploadMenu('{{ $loop->index }}', '{{ $value }}')">
                                📤 Upload
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
                    
                    <form action="{{ route('santri.dokumen-store') }}" method="POST" enctype="multipart/form-data" class="form-upload space-y-4" id="form-optional">
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
            <div class="flex gap-4 pt-4 lg:pt-6 border-t mt-4 lg:mt-6">
                <a href="{{ route('santri.dashboard') }}" class="bg-gray-400 text-white px-4 lg:px-6 py-2 lg:py-2 rounded hover:bg-gray-500 font-semibold transition text-sm">✅ Selesai</a>
            </div>
        </div>
    </div>

    <script>
        let uploadMenu = null;
        let selectedFormId = null;

        // Show upload menu (Upload File or Camera)
        function showUploadMenu(formIndex, tipeDokumen) {
            selectedFormId = 'form-' + formIndex;

            uploadMenu = document.createElement('div');
            uploadMenu.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            uploadMenu.innerHTML = `
                <div class="bg-white rounded-lg p-4 lg:p-8 max-w-sm w-full">
                    <h3 class="text-lg lg:text-xl font-bold mb-4 lg:mb-6 text-gray-800">📤 Upload Dokumen</h3>
                    
                    <div class="flex flex-col gap-3">
                        <button type="button" class="flex items-center justify-center gap-3 bg-blue-600 text-white px-4 lg:px-6 py-3 rounded hover:bg-blue-700 font-semibold transition text-sm touch-manipulation" onclick="chooseFile()">
                            📁 Pilih File
                        </button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-green-600 text-white px-4 lg:px-6 py-3 rounded hover:bg-green-700 font-semibold transition text-sm touch-manipulation" onclick="openCameraFromMenu()">
                            📷 Ambil Foto
                        </button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-gray-400 text-white px-4 lg:px-6 py-3 rounded hover:bg-gray-500 font-semibold transition text-sm touch-manipulation" onclick="closeUploadMenu()">
                            ❌ Batal
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(uploadMenu);
        }

        // Show upload menu for optional
        function showUploadMenuOptional() {
            selectedFormId = 'form-optional';

            const tipeDokumenInput = document.querySelector('#form-optional input[name="tipe_dokumen"]');
            if (!tipeDokumenInput.value) {
                alert('Isi nama dokumen terlebih dahulu!');
                return;
            }

            uploadMenu = document.createElement('div');
            uploadMenu.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            uploadMenu.innerHTML = `
                <div class="bg-white rounded-lg p-4 lg:p-8 max-w-sm w-full">
                    <h3 class="text-lg lg:text-xl font-bold mb-4 lg:mb-6 text-gray-800">📤 Upload Dokumen</h3>
                    
                    <div class="flex flex-col gap-3">
                        <button type="button" class="flex items-center justify-center gap-3 bg-blue-600 text-white px-4 lg:px-6 py-3 rounded hover:bg-blue-700 font-semibold transition text-sm touch-manipulation" onclick="chooseFile()">
                            📁 Pilih File
                        </button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-green-600 text-white px-4 lg:px-6 py-3 rounded hover:bg-green-700 font-semibold transition text-sm touch-manipulation" onclick="openCameraFromMenu()">
                            📷 Ambil Foto
                        </button>
                        <button type="button" class="flex items-center justify-center gap-3 bg-gray-400 text-white px-4 lg:px-6 py-3 rounded hover:bg-gray-500 font-semibold transition text-sm touch-manipulation" onclick="closeUploadMenu()">
                            ❌ Batal
                        </button>
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

        // File input change handler - auto submit
        document.addEventListener('change', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'file' && e.target.files[0]) {
                const form = e.target.closest('.form-upload');
                if (form && !form.classList.contains('hidden')) {
                    form.submit();
                }
            }
        });

        // Camera functionality
        let cameraStream = null;
        let cameraModal = null;

        function openCamera(inputId) {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('📷 Kamera tidak tersedia di device ini. Gunakan file upload.');
                return;
            }

            // Create modal
            cameraModal = document.createElement('div');
            cameraModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            cameraModal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-bold mb-4">📷 Ambil Foto</h3>
                    
                    <div style="position: relative;">
                        <video id="cameraVideo" class="w-full rounded border-2 border-gray-300 mb-4" style="max-height: 400px; object-fit: cover;"></video>
                        <div id="cameraLoading" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded" style="display: none;">
                            <p class="text-white font-semibold">⏳ Kamera Loading...</p>
                        </div>
                    </div>
                    
                    <canvas id="cameraCanvas" class="hidden"></canvas>
                    
                    <div class="flex gap-2">
                        <button type="button" id="captureBtn" onclick="capturePhoto('${inputId}')" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>📸 Ambil Foto</button>
                        <button type="button" onclick="closeCamera()" class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">❌ Batal</button>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-3 text-center">Posisikan objek dengan jelas di depan kamera</p>
                    <p id="cameraStatus" class="text-xs text-gray-600 mt-2 text-center">Mengakses kamera...</p>
                </div>
            `;
            document.body.appendChild(cameraModal);

            // Start camera
            const video = document.getElementById('cameraVideo');
            const loadingDiv = document.getElementById('cameraLoading');
            const captureBtn = document.getElementById('captureBtn');
            const statusText = document.getElementById('cameraStatus');

            // Show loading
            loadingDiv.style.display = 'flex';
            statusText.textContent = 'Mengakses kamera...';

            navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment',
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                },
                audio: false
            }).then(stream => {
                cameraStream = stream;
                video.srcObject = stream;

                // Wait for video to be ready
                video.onloadedmetadata = function() {
                    console.log('✅ Video metadata loaded: ' + video.videoWidth + 'x' + video.videoHeight);
                    video.play();
                    
                    // Wait a bit more for actual frame
                    setTimeout(() => {
                        loadingDiv.style.display = 'none';
                        statusText.textContent = '✅ Kamera siap! Tekan "Ambil Foto"';
                        captureBtn.disabled = false;
                    }, 500);
                };

                // Fallback: setTimeout jika onloadedmetadata tidak trigger
                setTimeout(() => {
                    if (video.videoWidth && video.videoHeight) {
                        console.log('✅ Video ready (via timeout)');
                        if (loadingDiv.style.display !== 'none') {
                            loadingDiv.style.display = 'none';
                            statusText.textContent = '✅ Kamera siap! Tekan "Ambil Foto"';
                            captureBtn.disabled = false;
                        }
                    }
                }, 2000);

            }).catch(err => {
                console.error('❌ Error accessing camera:', err);
                loadingDiv.style.display = 'none';
                
                let errorMsg = '';
                if (err.name === 'NotAllowedError') {
                    errorMsg = 'Akses kamera ditolak. Periksa permission di settings device Anda.';
                } else if (err.name === 'NotFoundError') {
                    errorMsg = 'Kamera tidak ditemukan di device ini.';
                } else if (err.name === 'NotReadableError') {
                    errorMsg = 'Kamera sedang digunakan aplikasi lain.';
                } else {
                    errorMsg = err.message;
                }
                
                statusText.innerHTML = '❌ ' + errorMsg;
                statusText.style.color = '#dc2626';
                captureBtn.textContent = '❌ Gagal';
                captureBtn.onclick = () => closeCamera();
                
                alert('❌ Gagal akses kamera:\n\n' + errorMsg + '\n\nGunakan file upload sebagai alternatif.');
                closeCamera();
            });
        }

        function capturePhoto(inputId) {
            const video = document.getElementById('cameraVideo');
            const canvas = document.getElementById('cameraCanvas');
            const ctx = canvas.getContext('2d');
            const fileInput = document.getElementById(inputId);

            // Verify video is ready
            if (!video.videoWidth || !video.videoHeight) {
                console.error('❌ Video belum siap - videoWidth:', video.videoWidth, 'videoHeight:', video.videoHeight);
                alert('⏳ Tunggu sebentar, kamera masih loading...');
                return;
            }

            try {
                // Set canvas size to match video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                console.log('📐 Canvas size: ' + canvas.width + 'x' + canvas.height);

                // Draw video frame to canvas
                ctx.drawImage(video, 0, 0);

                // Verify canvas has content
                const imageData = ctx.getImageData(0, 0, 1, 1);
                if (imageData.data[3] === 0) {
                    console.warn('⚠️ Canvas mungkin kosong, lanjutkan anyway...');
                }

                // Convert to blob and set to file input
                canvas.toBlob(blob => {
                    if (!blob) {
                        console.error('❌ Gagal membuat blob dari canvas');
                        alert('❌ Gagal mengambil foto. Coba lagi atau gunakan file upload.');
                        return;
                    }

                    console.log('✅ Blob berhasil dibuat: ' + blob.size + ' bytes');

                    try {
                        // Create FormData untuk di-submit langsung
                        const formData = new FormData();
                        const fileName = 'camera-' + Date.now() + '.jpg';
                        formData.append('file', blob, fileName);
                        formData.append('tipe_dokumen', document.querySelector('#' + inputId).closest('.form-upload').querySelector('input[name="tipe_dokumen"]').value);
                        formData.append('_method', 'POST');

                        // Get CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                         document.querySelector('input[name="_token"]')?.value;
                        if (csrfToken) {
                            formData.append('_token', csrfToken);
                        }

                        console.log('📤 Submitting photo...');
                        closeCamera();

                        // Submit form
                        const form = document.getElementById(selectedFormId);
                        const fileInputElement = form.querySelector('input[type="file"]');
                        
                        // Method 1: Try DataTransfer (Modern browsers)
                        try {
                            const dataTransfer = new DataTransfer();
                            const file = new File([blob], fileName, { type: 'image/jpeg' });
                            dataTransfer.items.add(file);
                            fileInputElement.files = dataTransfer.files;
                            console.log('✅ File set via DataTransfer');
                        } catch (e) {
                            console.warn('⚠️ DataTransfer tidak tersedia, menggunakan alternative method: ' + e.message);
                            // Method 2: Use fetch to upload directly
                            const form = document.getElementById(selectedFormId);
                            const action = form.getAttribute('action');
                            
                            fetch(action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('✅ Upload berhasil:', data);
                                if (data.success || data.message) {
                                    location.reload();
                                } else {
                                    alert('❌ ' + (data.error || 'Gagal upload'));
                                }
                            })
                            .catch(error => {
                                console.error('❌ Upload error:', error);
                                alert('❌ Error: ' + error.message);
                            });
                            return;
                        }

                        // Trigger change event
                        fileInputElement.dispatchEvent(new Event('change', { bubbles: true }));
                        console.log('📸 Foto berhasil ditangkap: ' + fileName);

                    } catch (e) {
                        console.error('❌ Error setting file: ' + e.message);
                        alert('❌ Error: ' + e.message);
                    }
                }, 'image/jpeg', 0.9);

            } catch (e) {
                console.error('❌ Capture error: ' + e.message);
                alert('❌ Error saat mengambil foto: ' + e.message);
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
