<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        // Cek apakah input adalah nomor HP atau email
        $fieldType = filter_var($login['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        // Coba login dengan email atau nomor HP
        $credentials = [
            $fieldType => $login['email'],
            'password' => $login['password']
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'petugas_pendaftaran' || Auth::user()->role === 'petugas_keuangan') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('santri.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email/No. HP atau password salah.'
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
