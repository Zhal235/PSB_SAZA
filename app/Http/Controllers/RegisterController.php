<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_telp' => [
                'required',
                'string',
                Rule::unique('users', 'phone'),
                function ($attribute, $value, $fail) {
                    // Validasi format nomor HP Indonesia
                    // Format yang diperbolehkan: 08xxx, 0812xxx, +62xxx, 62xxx
                    if (!preg_match('/^(08|\\+628|628|62)[0-9]{8,11}$/', $value)) {
                        $fail('Format nomor HP tidak valid. Gunakan format: 08123456789 atau +62812345678');
                    }
                }
            ],
            'password' => 'required|string|min:8|confirmed',
            'agree' => 'required|accepted',
        ], [
            'no_telp.unique' => 'Nomor HP ini sudah terdaftar',
            'password.confirmed' => 'Password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'agree.required' => 'Anda harus menyetujui syarat & ketentuan',
        ]);

        // Create user (calon santri)
        $user = User::create([
            'name' => $validated['nama'],
            'phone' => $validated['no_telp'],
            'email' => $validated['no_telp'] . '@santri.local', // Auto-generated email
            'password' => Hash::make($validated['password']),
            'role' => 'calon_santri',
        ]);

        // Auto login setelah register
        auth()->login($user);

        return redirect()->route('santri.dashboard')->with('success', '✅ Pendaftaran berhasil! Selamat datang!');
    }
}
