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

        return redirect()->route('santri.form-pendaftaran')
            ->with('success', '✅ Data pendaftaran berhasil disimpan!');
    }

    /**
     * Show santri pembayaran
     */
    public function pembayaran()
    {
        $calonSantri = CalonSantri::where('no_telp', auth()->user()->phone)->first();
        $pembayaran = $calonSantri ? $calonSantri->pembayaran : null;

        return view('santri.pembayaran', compact('pembayaran'));
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
}
