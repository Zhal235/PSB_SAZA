@extends('layouts.admin')

@section('title', 'Detail Calon Santri')
@section('page-title', 'Detail: ' . $calonSantri->nama)
@section('page-subtitle', 'No. Pendaftaran: ' . $calonSantri->no_pendaftaran)

@section('content')
    <div class="bg-white rounded-lg shadow max-w-4xl">
                    <div class="p-8 space-y-6">
                        <!-- Header Info -->
                        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">No. Pendaftaran</p>
                                <p class="text-2xl font-bold text-indigo-600">{{ $calonSantri->no_pendaftaran }}</p>
                                @if($calonSantri->user)
                                    <p class="text-xs text-green-600 mt-2">✓ Terdaftar dengan akun: {{ $calonSantri->user->name }}</p>
                                @else
                                    <p class="text-xs text-yellow-600 mt-2">⚠ Belum ada akun user terkait</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 text-sm">Jenjang & Status</p>
                                <div class="flex gap-2 justify-end mt-2">
                                    <span class="px-3 py-1 text-sm rounded-full font-bold
                                        @if($calonSantri->jenjang === 'MTs') bg-blue-100 text-blue-700
                                        @else bg-purple-100 text-purple-700
                                        @endif
                                    ">
                                        {{ $calonSantri->jenjang }}
                                    </span>
                                    <span class="px-4 py-1 text-sm rounded-full font-bold
                                        @if($calonSantri->status === 'baru') bg-blue-100 text-blue-700
                                        @elseif($calonSantri->status === 'proses') bg-yellow-100 text-yellow-700
                                        @elseif($calonSantri->status === 'lolos') bg-green-100 text-green-700
                                        @elseif($calonSantri->status === 'tidak_lolos') bg-red-100 text-red-700
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $calonSantri->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pribadi -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">📋 Data Pribadi</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Nama Lengkap</p>
                                    <p class="text-gray-800 text-lg">{{ $calonSantri->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Jenis Kelamin</p>
                                    <p class="text-gray-800 text-lg">{{ ucfirst($calonSantri->jenis_kelamin) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Tanggal Lahir</p>
                                    <p class="text-gray-800 text-lg">{{ $calonSantri->tanggal_lahir->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Umur</p>
                                    <p class="text-gray-800 text-lg">{{ $calonSantri->tanggal_lahir->diff(\Carbon\Carbon::now())->y }} tahun</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">📞 Kontak & Alamat</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Email</p>
                                    <p class="text-gray-800 text-lg">{{ $calonSantri->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">No. Telepon</p>
                                    <p class="text-gray-800 text-lg">{{ $calonSantri->no_telp }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Alamat</p>
                                    <p class="text-gray-800">{{ $calonSantri->alamat }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pendidikan -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">🎓 Pendidikan</h3>
                            <div>
                                <p class="text-gray-600 text-sm font-semibold">Asal Sekolah</p>
                                <p class="text-gray-800 text-lg">{{ $calonSantri->asal_sekolah }}</p>
                            </div>
                        </div>

                        <!-- Catatan -->
                        @if($calonSantri->catatan)
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">📝 Catatan</h3>
                                <p class="text-gray-800">{{ $calonSantri->catatan }}</p>
                            </div>
                        @endif

                        <!-- Timeline -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b-2 border-indigo-600">🕒 Waktu</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Dibuat Pada</p>
                                    <p class="text-gray-800">{{ $calonSantri->created_at->format('d F Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Diperbarui Pada</p>
                                    <p class="text-gray-800">{{ $calonSantri->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-4 border-t">
                            <a
                                href="{{ route('admin.calon-santri.edit', $calonSantri) }}"
                                class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 font-semibold transition"
                            >
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('admin.calon-santri.destroy', $calonSantri) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 font-semibold transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                            <a
                                href="{{ route('admin.calon-santri.index') }}"
                                class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition ml-auto"
                            >
                                ← Kembali
                            </a>
                        </div>
                    </div>
                </div>
    </div>
@endsection
