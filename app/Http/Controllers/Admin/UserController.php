<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Hanya tampilkan user petugas (admin, petugas_pendaftaran, petugas_keuangan)
        // TIDAK termasuk santri
        $users = User::with('role')
                    ->whereHas('role', function($query) {
                        $query->whereIn('name', ['admin', 'petugas_pendaftaran', 'petugas_keuangan']);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Ambil roles yang bisa dipilih (hanya petugas, bukan santri)
        $roles = Role::whereIn('name', ['admin', 'petugas_pendaftaran', 'petugas_keuangan'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Auto verify for admin users
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('role.permissions');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Ambil roles yang bisa dipilih
        $roles = Role::whereIn('name', ['admin', 'petugas_pendaftaran', 'petugas_keuangan'])->get();
        $user->load('role');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Proteksi: tidak bisa hapus akun santri
        if ($user->role && in_array($user->role->name, ['santri', 'calon_santri'])) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak dapat menghapus akun santri dari halaman ini.');
        }

        // Proteksi: tidak bisa hapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User petugas berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users.index')
                        ->with('success', "User berhasil {$status}.");
    }
}