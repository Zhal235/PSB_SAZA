<?php

namespace App\Http\Controllers;

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
        return view('santri.dashboard');
    }
}
