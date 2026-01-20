@extends('layouts.admin')

@section('title', 'Pengaturan Bank')
@section('page-title', '🏦 Pengaturan Rekening Bank')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Kelola daftar rekening bank yang digunakan untuk penerimaan pembayaran</p>
@endsection

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Add Button -->
    <div class="mb-6">
        <a href="{{ route('admin.bank-settings.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold">
            ➕ Tambah Rekening Bank
        </a>
    </div>

    <!-- Banks Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($banks->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Bank</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Nomor Rekening</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Atas Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Deskripsi</th>
                        <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($banks as $bank)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $bank->bank_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-mono text-sm text-gray-700">{{ $bank->account_number }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-700">{{ $bank->account_holder }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $bank->description ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($bank->is_active)
                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        ✅ Aktif
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        ❌ Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.bank-settings.edit', $bank) }}" 
                                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.bank-settings.destroy', $bank) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm" 
                                            onclick="return confirm('Yakin ingin menghapus rekening ini?')">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-8 text-center text-gray-500">
                <p class="mb-2">📭 Belum ada rekening bank yang terdaftar</p>
                <a href="{{ route('admin.bank-settings.create') }}" class="text-blue-600 hover:underline font-semibold">
                    Tambah Rekening Bank Sekarang
                </a>
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
        <p class="text-sm text-blue-700 font-semibold mb-2">💡 Informasi:</p>
        <ul class="text-sm text-blue-700 ml-4 space-y-1 list-disc">
            <li>Rekening yang ditandai <strong>Aktif</strong> akan ditampilkan kepada santri saat pembayaran</li>
            <li>Gunakan field <strong>Deskripsi</strong> untuk catatan tambahan, contoh: "Untuk pendaftaran reguler"</li>
            <li>Nomor HP dapat diisi untuk kemudahan kontak verifikasi pembayaran</li>
        </ul>
    </div>
@endsection
