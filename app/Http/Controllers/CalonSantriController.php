<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use App\Models\Pembayaran;
use App\Models\User;
use App\Exports\CalonSantriExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class CalonSantriController extends Controller
{
    // List semua calon santri - filter berdasarkan jenjang
    public function index(Request $request)
    {
        $jenjang = $request->query('jenjang', 'MTs'); // Default MTs
        $calonSantri = CalonSantri::where('jenjang', $jenjang)->latest()->paginate(15);
        return view('admin.calon-santri.index', compact('calonSantri', 'jenjang'));
    }

    // Show form create dengan modal jenjang
    public function create(Request $request)
    {
        // Jika jenjang sudah dipilih dari modal
        $jenjang = $request->query('jenjang');
        if (!$jenjang) {
            return view('admin.calon-santri.select-jenjang');
        }
        return view('admin.calon-santri.create', compact('jenjang'));
    }

    // Store ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenjang' => 'required|in:MTs,SMK',
            'nisn' => 'nullable|string|max:20',
            'nik_santri' => 'nullable|string|max:20',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
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
            'no_telp' => 'required|string|max:15',
            'phone_type' => 'nullable|in:ayah,ibu',
            'status' => 'required|in:baru,proses,lolos,tidak_lolos',
            'catatan' => 'nullable|string'
        ]);

        // Auto-generate nomor pendaftaran
        $validated['no_pendaftaran'] = CalonSantri::generateNoPendaftaran();

        // Tentukan nomor HP yang akan digunakan untuk akun
        $phoneToUse = null;
        $phoneType = $request->input('phone_type');

        if ($phoneType === 'ayah' && !empty($validated['hp_ayah'])) {
            $phoneToUse = $validated['hp_ayah'];
        } elseif ($phoneType === 'ibu' && !empty($validated['hp_ibu'])) {
            $phoneToUse = $validated['hp_ibu'];
        } elseif (!empty($validated['hp_ayah'])) {
            // Default ke HP ayah jika ada
            $phoneToUse = $validated['hp_ayah'];
            $validated['phone_type'] = 'ayah';
        } elseif (!empty($validated['hp_ibu'])) {
            // Fallback ke HP ibu
            $phoneToUse = $validated['hp_ibu'];
            $validated['phone_type'] = 'ibu';
        }

        // Buat atau update user dengan HP yang sudah ditentukan
        if ($phoneToUse) {
            $user = User::updateOrCreate(
                ['phone' => $phoneToUse],
                [
                    'name' => $validated['nama'],
                    'email' => strtolower(str_replace(' ', '.', $validated['nama'])) . '@psb-saza.local',
                    'password' => Hash::make('12345678'),
                    'role' => 'calon_santri',
                    'jenjang' => $validated['jenjang'],
                ]
            );
            $validated['user_id'] = $user->id;
        }

        $calonSantri = CalonSantri::create($validated);

        // Otomatis buat pembayaran record
        $totalItems = \App\Models\PembayaranItem::where('status', 'active')->sum('nominal');
        Pembayaran::create([
            'calon_santri_id' => $calonSantri->id,
            'total_amount' => $totalItems,
            'paid_amount' => 0,
            'remaining_amount' => $totalItems,
            'status' => 'belum_bayar',
            'due_date' => now()->addDays(7)
        ]);

        return redirect()->route('dokumen.create', $calonSantri)
            ->with('success', 'Data calon santri berhasil ditambahkan! Akun sudah dibuat dengan HP: ' . $phoneToUse . ' dan password default: 12345678');
    }

    // Show form edit
    public function edit(CalonSantri $calonSantri)
    {
        return view('admin.calon-santri.edit', compact('calonSantri'));
    }

    // Update data
    public function update(Request $request, CalonSantri $calonSantri)
    {
        $validated = $request->validate([
            'nisn' => 'nullable|string|max:20',
            'nik_santri' => 'nullable|string|max:20',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'provinsi' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
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
            'no_telp' => 'required|string|max:15',
            'phone_type' => 'nullable|in:ayah,ibu',
            'status' => 'required|in:baru,proses,lolos,tidak_lolos',
            'catatan' => 'nullable|string'
        ]);

        // Handle phone type change
        $phoneType = $request->input('phone_type');
        $phoneToUse = null;

        if ($phoneType === 'ayah' && !empty($validated['hp_ayah'])) {
            $phoneToUse = $validated['hp_ayah'];
        } elseif ($phoneType === 'ibu' && !empty($validated['hp_ibu'])) {
            $phoneToUse = $validated['hp_ibu'];
        } elseif (!empty($validated['hp_ayah'])) {
            $phoneToUse = $validated['hp_ayah'];
            $validated['phone_type'] = 'ayah';
        } elseif (!empty($validated['hp_ibu'])) {
            $phoneToUse = $validated['hp_ibu'];
            $validated['phone_type'] = 'ibu';
        }

        // Update atau buat user baru jika nomor HP berubah
        if ($phoneToUse) {
            $user = User::updateOrCreate(
                ['phone' => $phoneToUse],
                [
                    'name' => $validated['nama'],
                    'email' => strtolower(str_replace(' ', '.', $validated['nama'])) . '@psb-saza.local',
                    'role' => 'calon_santri',
                    'jenjang' => $calonSantri->jenjang,
                ]
            );
            $validated['user_id'] = $user->id;
        }

        $calonSantri->update($validated);

        return redirect()->route('dokumen.create', $calonSantri)
            ->with('success', 'Data calon santri berhasil diperbarui! Silakan update dokumen.');
    }

    // Reset password santri jadi 12345678
    public function resetPassword(CalonSantri $calonSantri)
    {
        // Cari user berdasarkan nomor telepon
        $user = User::where('phone', $calonSantri->no_telp)->first();
        
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan!');
        }

        // Reset password jadi 12345678
        $user->password = Hash::make('12345678');
        $user->save();

        return back()->with('success', 'Password berhasil direset menjadi: 12345678');
    }

    // Delete data
    public function destroy(CalonSantri $calonSantri)
    {
        // Hapus user terkait jika ada
        $user = User::where('phone', $calonSantri->no_telp)->first();
        if ($user) {
            $user->delete();
        }

        // Hapus data calon santri
        $calonSantri->delete();

        return redirect()->route('admin.calon-santri.index')
            ->with('success', 'Data calon santri dan akun user berhasil dihapus!');
    }

    // Show detail calon santri
    public function show(CalonSantri $calonSantri)
    {
        return view('admin.calon-santri.show', compact('calonSantri'));
    }

    // Export ke Excel (untuk SIMPELS)
    public function export(Request $request)
    {
        $jenjang = $request->query('jenjang', 'MTs');
        $fileName = 'CalonSantri_' . $jenjang . '_' . now()->format('d-m-Y-H-i-s') . '.xlsx';
        
        return Excel::download(new CalonSantriExport($jenjang), $fileName);
    }
}
