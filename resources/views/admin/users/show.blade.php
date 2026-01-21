@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', '👁️ Detail User')
@section('page-subtitle', 'Informasi lengkap user')

@section('content')
    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-2xl" 
                         style="background-color: {{ $user->role === 'admin' ? '#00a0a0' : ($user->role === 'petugas_pendaftaran' ? '#10b981' : '#8b5cf6') }}">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            @if($user->role === 'admin')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">👑 Admin</span>
                            @elseif($user->role === 'petugas_pendaftaran')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">📋 Petugas Pendaftaran</span>
                            @elseif($user->role === 'petugas_keuangan')
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">💰 Petugas Keuangan</span>
                            @endif
                            
                            @if($user->is_active)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">✅ Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">❌ Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Info -->
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">📋 Informasi Personal</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <p class="text-gray-800 font-semibold">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-800">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Nomor HP</label>
                                <p class="text-gray-800">{{ $user->phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Role</label>
                                <p class="text-gray-800 font-semibold">
                                    @if($user->role === 'admin')
                                        👑 Admin
                                    @elseif($user->role === 'petugas_pendaftaran')
                                        📋 Petugas Pendaftaran
                                    @elseif($user->role === 'petugas_keuangan')
                                        💰 Petugas Keuangan
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">⚙️ Informasi Sistem</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Status Akun</label>
                                <p class="text-gray-800 font-semibold">
                                    @if($user->is_active)
                                        <span class="text-green-600">✅ Aktif</span>
                                    @else
                                        <span class="text-red-600">❌ Nonaktif</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Tanggal Bergabung</label>
                                <p class="text-gray-800">{{ $user->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Terakhir Diperbarui</label>
                                <p class="text-gray-800">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email Terverifikasi</label>
                                <p class="text-gray-800">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">✅ Terverifikasi</span><br>
                                        <small class="text-gray-500">{{ $user->email_verified_at->format('d F Y, H:i') }}</small>
                                    @else
                                        <span class="text-yellow-600">⏳ Belum Terverifikasi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Description -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h5 class="font-bold text-gray-700 mb-2">📖 Deskripsi Role</h5>
                    @if($user->role === 'admin')
                        <p class="text-sm text-gray-600">Memiliki akses penuh ke seluruh sistem termasuk manajemen user, data santri, verifikasi dokumen, dan laporan keuangan.</p>
                    @elseif($user->role === 'petugas_pendaftaran')
                        <p class="text-sm text-gray-600">Bertugas mengelola data pendaftar, verifikasi dokumen, dan proses administrasi pendaftaran santri baru.</p>
                    @elseif($user->role === 'petugas_keuangan')
                        <p class="text-sm text-gray-600">Bertugas mengelola pembayaran, verifikasi transfer, pencatatan keuangan, dan pembuatan laporan keuangan.</p>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t mt-6">
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 font-semibold transition">
                        ✏️ Edit User
                    </a>
                    
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-6 py-2 {{ $user->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition font-semibold">
                                {{ $user->is_active ? '⏸️ Nonaktifkan' : '▶️ Aktifkan' }}
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 font-semibold transition">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection