<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    /**
     * Show jenjang selection page
     */
    public function selectJenjang()
    {
        // Jika sudah memilih jenjang, redirect ke dashboard
        if (auth()->user()->has_selected_jenjang) {
            return redirect()->route('santri.dashboard');
        }

        return view('santri.select-jenjang');
    }

    /**
     * Save jenjang selection
     */
    public function saveJenjang(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => 'required|in:MTs,SMK',
        ]);

        // Update user jenjang
        auth()->user()->update([
            'jenjang' => $validated['jenjang'],
            'has_selected_jenjang' => true,
        ]);

        return redirect()->route('santri.dashboard')->with('success', '✅ Jenjang berhasil dipilih! Selamat datang!');
    }

    /**
     * Show santri dashboard
     */
    public function dashboard()
    {
        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();
        $pembayaran = $calonSantri ? $calonSantri->pembayaran : null;

        return view('santri.dashboard', compact('calonSantri', 'pembayaran'));
    }

    /**
     * Show form pendaftaran
     */
    public function formPendaftaran()
    {
        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();
        return view('santri.form-pendaftaran', compact('calonSantri'));
    }

    /**
     * Save form pendaftaran
     */
    public function savePendaftaran(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nisn' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'asal_sekolah' => 'required|string|max:255',
            'hobi' => 'nullable|string|max:255',
            'cita_cita' => 'nullable|string|max:255',
            'jumlah_saudara' => 'nullable|integer|min:0',
            'no_kk' => 'nullable|string|max:20',
            'pendapatan_keluarga' => 'nullable|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nik_ayah' => 'nullable|string|max:20',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'hp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'required|string|max:255',
            'nik_ibu' => 'nullable|string|max:20',
            'pendidikan_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'hp_ibu' => 'nullable|string|max:15',
        ]);

        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();

        if ($calonSantri) {
            // Update existing
            $calonSantri->update($validated);
        } else {
            // Create new
            $validated['no_pendaftaran'] = CalonSantri::generateNoPendaftaran();
            $validated['no_telp'] = auth()->user()->phone;
            $validated['jenjang'] = auth()->user()->jenjang;
            $validated['status'] = 'baru';
            $calonSantri = CalonSantri::create($validated);

            // Create pembayaran record
            $totalAmount = 0; // TODO: Hitung dari items
            $calonSantri->pembayaran()->create([
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'status' => 'belum_bayar',
            ]);
        }

        return redirect()->route('santri.dokumen-upload', $calonSantri)
            ->with('success', '✅ Data pendaftaran berhasil disimpan! Silakan upload dokumen.');
    }

    /**
     * Show santri pembayaran
     */
    public function pembayaran()
    {
        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();
        $pembayaran = $calonSantri ? $calonSantri->pembayaran()->with('records')->first() : null;
        
        // Load active pembayaran items
        $items = \App\Models\PembayaranItem::where('status', 'active')->get();

        return view('santri.pembayaran', compact('pembayaran', 'items'));
    }

    /**
     * Show pembayaran invoice
     */
    public function pembayaranInvoice($pembayaranId)
    {
        $pembayaran = auth()->user()->calonSantri->pembayaran;
        
        if (!$pembayaran || $pembayaran->id != $pembayaranId) {
            abort(403);
        }

        return view('santri.pembayaran-invoice', compact('pembayaran'));
    }

    /**
     * Show dokumen upload page
     */
    public function dokumenUpload()
    {
        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();
        
        if (!$calonSantri) {
            return redirect()->route('santri.form-pendaftaran')
                ->with('error', 'Silakan lengkapi form pendaftaran terlebih dahulu');
        }

        $dokumenTypes = [
            'Foto' => 'Foto 4x6 cm',
            'Ijazah' => 'Scan Ijazah',
            'Akte Kelahiran' => 'Scan Akte Kelahiran',
            'KTP Ayah' => 'Scan KTP Ayah',
            'KTP Ibu' => 'Scan KTP Ibu',
            'Kartu Keluarga' => 'Scan Kartu Keluarga'
        ];

        return view('santri.dokumen-upload', compact('calonSantri', 'dokumenTypes'));
    }

    /**
     * Store dokumen upload from santri
     */
    public function dokumenStore(\Illuminate\Http\Request $request)
    {
        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();
        
        if (!$calonSantri) {
            return back()->with('error', 'Data calon santri tidak ditemukan');
        }

        try {
            $validated = $request->validate([
                'tipe_dokumen' => 'required|string|max:100',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png'
            ]);

            $file = $request->file('file');
            if (!$file) {
                return back()->with('error', 'File tidak ada di request!');
            }

            $originalName = $file->getClientOriginalName();
            $originalSize = $file->getSize();
            $ext = pathinfo($originalName, PATHINFO_EXTENSION);
            
            // Buat nama file yang descriptive: nama_santri_tipe_dokumen.ext
            $safeNamaSantri = preg_replace('/[^a-z0-9]+/i', '_', $calonSantri->nama);
            $safeNamaSantri = trim($safeNamaSantri, '_');
            $safeTipeDokumen = preg_replace('/[^a-z0-9]+/i', '_', $validated['tipe_dokumen']);
            $safeTipeDokumen = trim($safeTipeDokumen, '_');
            $filename = strtolower($safeNamaSantri . '_' . $safeTipeDokumen . '.' . $ext);

            \Log::info('=== UPLOAD START (SANTRI) ===');
            \Log::info('File: ' . $originalName . ' (' . round($originalSize / 1024 / 1024, 2) . 'MB)');
            \Log::info('Tipe dokumen: ' . $validated['tipe_dokumen']);

            // Baca file content langsung
            $fileContent = file_get_contents($file->getRealPath());
            $fileSize = strlen($fileContent);

            \Log::info('File content size: ' . round($fileSize / 1024 / 1024, 2) . 'MB');

            // Compress jika perlu
            if ($fileSize > 2 * 1024 * 1024 && in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                \Log::info('Compression needed, compressing...');
                
                $tempPath = tempnam(sys_get_temp_dir(), 'psb_');
                file_put_contents($tempPath, $fileContent);

                try {
                    // Cek apakah Intervention Image tersedia
                    if (class_exists('Intervention\Image\ImageManager')) {
                        $image = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                        $img = $image->read($tempPath);
                        $img->resize(1200, 1200, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $img->toJpeg(quality: 75)->save($tempPath);

                        if (filesize($tempPath) > 2 * 1024 * 1024) {
                            $img = $image->read($tempPath);
                            $img->toJpeg(quality: 60)->save($tempPath);
                        }

                        if (filesize($tempPath) > 2 * 1024 * 1024) {
                            $img = $image->read($tempPath);
                            $img->resize(800, 800, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $img->toJpeg(quality: 50)->save($tempPath);
                        }
                    } else {
                        // Fallback: simple compression with GD
                        \Log::info('Using GD for compression');
                        $this->compressImageGD($tempPath, $file->getMimeType());
                    }

                    $fileContent = file_get_contents($tempPath);
                    $fileSize = strlen($fileContent);
                    unlink($tempPath);

                    \Log::info('Compression done: ' . round($fileSize / 1024 / 1024, 2) . 'MB');
                } catch (\Exception $e) {
                    \Log::error('Compression error: ' . $e->getMessage());
                    if (file_exists($tempPath)) unlink($tempPath);
                    // Continue without compression jika error
                }
            }

            // Cek ukuran final PDF
            if ($fileSize > 2 * 1024 * 1024 && $file->getMimeType() === 'application/pdf') {
                return back()->with('error', 'File PDF terlalu besar! (max 2MB) File Anda: ' . round($fileSize / 1024 / 1024, 2) . 'MB');
            }

            // Hapus dokumen lama
            $old = \App\Models\Dokumen::where('calon_santri_id', $calonSantri->id)
                ->where('tipe_dokumen', $validated['tipe_dokumen'])
                ->first();

            if ($old) {
                \Log::info('Deleting old document: ' . $old->file_path);
                \Storage::disk('public')->delete($old->file_path);
                $old->delete();
            }

            // Simpan file
            $path = 'dokumen_santri/' . $filename;
            \Storage::disk('public')->put($path, $fileContent);

            // Verify
            if (!\Storage::disk('public')->exists($path)) {
                \Log::error('File save failed - file not found after save');
                return back()->with('error', 'Gagal menyimpan file!');
            }

            // Save to DB
            \App\Models\Dokumen::create([
                'calon_santri_id' => $calonSantri->id,
                'tipe_dokumen' => $validated['tipe_dokumen'],
                'file_path' => $path
            ]);

            \Log::info('=== UPLOAD SUCCESS (SANTRI) ===');
            \Log::info('Saved as: ' . $path . ' (' . round($fileSize / 1024 / 1024, 2) . 'MB)');

            return back()->with('success', '✅ ' . $validated['tipe_dokumen'] . ' berhasil! (' . round($fileSize / 1024 / 1024, 2) . 'MB)');

        } catch (\Exception $e) {
            \Log::error('=== UPLOAD ERROR (SANTRI) ===');
            \Log::error('Exception: ' . get_class($e));
            \Log::error('Message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());

            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Compress image using GD library
     */
    private function compressImageGD($filePath, $mimeType)
    {
        try {
            if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                $image = imagecreatefromjpeg($filePath);
            } elseif ($mimeType === 'image/png') {
                $image = imagecreatefrompng($filePath);
            } else {
                return;
            }

            if (!$image) return;

            // Get current dimensions
            $width = imagesx($image);
            $height = imagesy($image);

            // Calculate new dimensions
            $maxWidth = 1200;
            $maxHeight = 1200;

            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            // Save compressed
            if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                imagejpeg($image, $filePath, 75);
            } elseif ($mimeType === 'image/png') {
                imagepng($image, $filePath, 7);
            }

            imagedestroy($image);
            \Log::info('GD compression done');
        } catch (\Exception $e) {
            \Log::error('GD compression error: ' . $e->getMessage());
        }
    }
}
