<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class DokumenController extends Controller
{
    // Halaman verifikasi dokumen
    public function index(Request $request)
    {
        $jenjang = $request->get('jenjang', 'MTs');
        $hardcopy = $request->get('hardcopy', 'semua'); // semua, sudah, belum
        
        $query = CalonSantri::where('jenjang', $jenjang)->with('dokumens');
        
        if ($hardcopy === 'sudah') {
            // Cari yang semua dokumen wajibnya sudah diterima hardcopy
            $query->whereHas('dokumens', function ($q) {
                $q->where('tipe_dokumen', 'Foto')->where('hardcopy_diterima', true);
            })->whereHas('dokumens', function ($q) {
                $q->where('tipe_dokumen', 'Ijazah')->where('hardcopy_diterima', true);
            });
        } elseif ($hardcopy === 'belum') {
            // Cari yang ada dokumen wajib belum diterima hardcopy
            $query->whereDoesntHave('dokumens', function ($q) {
                $q->whereIn('tipe_dokumen', ['Foto', 'Ijazah', 'Akte Kelahiran', 'KTP Ayah', 'KTP Ibu', 'Kartu Keluarga'])
                  ->where('hardcopy_diterima', true);
            });
        }
        
        $calonSantri = $query->get();
        
        // Hitung summary untuk MTs
        $mtsTotal = CalonSantri::where('jenjang', 'MTs')->count();
        $mtsSudah = CalonSantri::where('jenjang', 'MTs')->get()->filter(function($santri) {
            return $santri->dokumens->whereIn('tipe_dokumen', ['Foto', 'Ijazah', 'Akte Kelahiran', 'KTP Ayah', 'KTP Ibu', 'Kartu Keluarga'])
                        ->where('hardcopy_diterima', true)->count() === 6;
        })->count();
        
        // Hitung summary untuk SMK
        $smkTotal = CalonSantri::where('jenjang', 'SMK')->count();
        $smkSudah = CalonSantri::where('jenjang', 'SMK')->get()->filter(function($santri) {
            return $santri->dokumens->whereIn('tipe_dokumen', ['Foto', 'Ijazah', 'Akte Kelahiran', 'KTP Ayah', 'KTP Ibu', 'Kartu Keluarga'])
                        ->where('hardcopy_diterima', true)->count() === 6;
        })->count();
        
        return view('admin.verifikasi-dokumen.index', compact(
            'calonSantri', 'jenjang', 'hardcopy', 
            'mtsTotal', 'mtsSudah', 'smkTotal', 'smkSudah'
        ));
    }

    // Tampilkan form upload dokumen
    public function create(CalonSantri $calonSantri)
    {
        $dokumenTypes = [
            'Foto' => 'Foto 4x6 cm',
            'Ijazah' => 'Scan Ijazah',
            'Akte Kelahiran' => 'Scan Akte Kelahiran',
            'KTP Ayah' => 'Scan KTP Ayah',
            'KTP Ibu' => 'Scan KTP Ibu',
            'Kartu Keluarga' => 'Scan Kartu Keluarga'
        ];
        
        return view('admin.dokumen.create', compact('calonSantri', 'dokumenTypes'));
    }

    // Upload dokumen dengan auto compress
    public function store(Request $request, CalonSantri $calonSantri)
    {
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

            \Log::info('=== UPLOAD START ===');
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
            $old = Dokumen::where('calon_santri_id', $calonSantri->id)
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
            Dokumen::create([
                'calon_santri_id' => $calonSantri->id,
                'tipe_dokumen' => $validated['tipe_dokumen'],
                'file_path' => $path
            ]);

            \Log::info('=== UPLOAD SUCCESS ===');
            \Log::info('Saved as: ' . $path . ' (' . round($fileSize / 1024 / 1024, 2) . 'MB)');

            return back()->with('success', '✅ ' . $validated['tipe_dokumen'] . ' berhasil! (' . round($fileSize / 1024 / 1024, 2) . 'MB)');

        } catch (\Exception $e) {
            \Log::error('=== UPLOAD ERROR ===');
            \Log::error('Exception: ' . get_class($e));
            \Log::error('Message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());

            return back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    // Toggle status hardcopy per dokumen
    public function toggleHardcopy(Request $request)
    {
        $dokumen = Dokumen::findOrFail($request->dokumen_id);
        
        $dokumen->update([
            'hardcopy_diterima' => $request->hardcopy_diterima,
            'tanggal_terima_hardcopy' => $request->hardcopy_diterima ? now() : null
        ]);

        return response()->json(['success' => true]);
    }

    // Hapus dokumen
    public function destroy(Dokumen $dokumen)
    {
        // Hapus file dari storage
        if (\Storage::disk('public')->exists($dokumen->file_path)) {
            \Storage::disk('public')->delete($dokumen->file_path);
        }

        $calonSantri = $dokumen->calonSantri;
        
        // Hapus record dari database
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    // Helper: Compress image using GD library
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
