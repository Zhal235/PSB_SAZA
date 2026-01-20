<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

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
            'status' => 'required|in:baru,proses,lolos,tidak_lolos',
            'catatan' => 'nullable|string'
        ]);

        // Auto-generate nomor pendaftaran
        $validated['no_pendaftaran'] = CalonSantri::generateNoPendaftaran();

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
            ->with('success', 'Data calon santri berhasil ditambahkan! Silakan upload dokumen.');
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
            'status' => 'required|in:baru,proses,lolos,tidak_lolos',
            'catatan' => 'nullable|string'
        ]);

        $calonSantri->update($validated);

        return redirect()->route('dokumen.create', $calonSantri)
            ->with('success', 'Data calon santri berhasil diperbarui! Silakan update dokumen.');
    }

    // Delete data
    public function destroy(CalonSantri $calonSantri)
    {
        $calonSantri->delete();

        return redirect()->route('admin.calon-santri.index')
            ->with('success', 'Data calon santri berhasil dihapus!');
    }

    // Show detail calon santri
    public function show(CalonSantri $calonSantri)
    {
        return view('admin.calon-santri.show', compact('calonSantri'));
    }
}
