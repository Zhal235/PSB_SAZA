<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'no_telp' => 'required|string|unique:users,phone|regex:/^(\+62|62|0)[0-9]{9,12}$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'agree' => 'required|accepted',
        ], [
            'no_telp.unique' => 'Nomor HP ini sudah terdaftar',
            'no_telp.regex' => 'Format nomor HP tidak valid',
            'email.unique' => 'Email ini sudah terdaftar',
            'password.confirmed' => 'Password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'agree.required' => 'Anda harus menyetujui syarat & ketentuan',
        ]);

        // Create user (calon santri)
        $user = User::create([
            'name' => $validated['nama'],
            'phone' => $validated['no_telp'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'calon_santri',
        ]);

        // Auto login setelah register
        auth()->login($user);

        return redirect()->route('santri.dashboard')->with('success', '✅ Pendaftaran berhasil! Selamat datang!');
    }
}
