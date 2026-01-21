@extends('layouts.admin')

@section('title', 'Manajemen User Petugas')
@section('page-title', '👥 Manajemen User Petugas')
@section('page-subtitle', 'Kelola akun admin, petugas pendaftaran dan keuangan')

@section('top-bar-action')
    <a href="{{ route('admin.users.create') }}" class="px-6 py-2 text-white rounded-lg hover:shadow-lg transition font-semibold" style="background-color: #00a0a0;">
        ➕ Tambah Petugas
    </a>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg shadow">
            <h3 class="text-blue-600 text-sm font-semibold mb-2">Total Admin</h3>
            <p class="text-2xl font-bold text-blue-800">{{ $users->where('role', 'admin')->count() }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-lg shadow">
            <h3 class="text-green-600 text-sm font-semibold mb-2">Petugas Pendaftaran</h3>
            <p class="text-2xl font-bold text-green-800">{{ $users->where('role', 'petugas_pendaftaran')->count() }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-lg shadow">
            <h3 class="text-purple-600 text-sm font-semibold mb-2">Petugas Keuangan</h3>
            <p class="text-2xl font-bold text-purple-800">{{ $users->where('role', 'petugas_keuangan')->count() }}</p>
        </div>
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-lg shadow">
            <h3 class="text-yellow-600 text-sm font-semibold mb-2">User Aktif</h3>
            <p class="text-2xl font-bold text-yellow-800">{{ $users->where('is_active', true)->count() }}</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">Daftar User Petugas Sistem</h3>
            <p class="text-gray-600 text-sm mt-1">Kelola akun admin dan petugas (bukan akun santri/calon santri)</p>
        </div>

        <!-- Info Box -->
        <div class="px-6 py-4 bg-blue-50 border-b">
            <p class="text-sm text-blue-700">
                <span class="font-semibold">ℹ️ Info:</span> Halaman ini khusus untuk mengelola akun petugas sistem. 
                Untuk mengelola data santri, gunakan menu "Kelola Pendaftar".
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">Nama</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Email & HP</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Role</th>
                        <th class="text-center p-4 font-semibold text-gray-700">Status</th>
                        <th class="text-center p-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" 
                                         style="background-color: {{ $user->role === 'admin' ? '#00a0a0' : ($user->role === 'petugas_pendaftaran' ? '#10b981' : '#8b5cf6') }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">Bergabung {{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="text-gray-800">{{ $user->email }}</p>
                                <p class="text-sm text-gray-600">{{ $user->phone }}</p>
                            </td>
                            <td class="p-4">
                                @if($user->role === 'admin')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">👑 Admin</span>
                                @elseif($user->role === 'petugas_pendaftaran')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">📋 Petugas Pendaftaran</span>
                                @elseif($user->role === 'petugas_keuangan')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">💰 Petugas Keuangan</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($user->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✅ Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">❌ Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-xs font-semibold">
                                        ✏️ Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 {{ $user->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded transition text-xs font-semibold">
                                                {{ $user->is_active ? '⏸️' : '▶️' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Yakin hapus petugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-xs font-semibold">
                                                🗑️
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded text-xs font-semibold">
                                            👤 Anda
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                <div class="text-6xl mb-4">👥</div>
                                <p class="text-lg font-semibold mb-2">Belum ada petugas yang terdaftar</p>
                                <p class="text-sm mb-4">Tambahkan akun untuk admin atau petugas pendaftaran/keuangan</p>
                                <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:underline font-semibold">Tambah petugas pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-6 border-t">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection