<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon Santri - PSB SAZA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-indigo-600 text-white p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">PSB SAZA</h1>
                <p class="text-indigo-200 text-sm">Admin Panel</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-2 rounded bg-indigo-700 hover:bg-indigo-800 transition font-semibold">
                    👥 Kelola Pendaftar
                </a>
                <a href="{{ route('verifikasi-dokumen.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📋 Verifikasi Dokumen
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    📊 Laporan
                </a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    ⚙️ Pengaturan
                </a>
            </nav>

            <hr class="my-6 border-indigo-400">

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button
                    type="submit"
                    class="w-full px-4 py-2 rounded bg-red-500 hover:bg-red-600 transition font-semibold text-sm"
                >
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow p-6 flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-2xl font-bold text-gray-800">Kelola Calon Santri</h2>
                <a href="{{ route('admin.calon-santri.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition font-semibold">
                    ➕ Tambah Calon Santri
                </a>
            </div>

            <!-- Tabs untuk Jenjang -->
            <div class="bg-white border-b border-gray-200 px-6">
                <div class="flex space-x-2">
                    <a href="{{ route('admin.calon-santri.index', ['jenjang' => 'MTs']) }}" 
                        class="px-6 py-4 font-semibold border-b-2 transition
                        {{ $jenjang === 'MTs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                        🏫 MTs ({{ \App\Models\CalonSantri::where('jenjang', 'MTs')->count() }})
                    </a>
                    <a href="{{ route('admin.calon-santri.index', ['jenjang' => 'SMK']) }}" 
                        class="px-6 py-4 font-semibold border-b-2 transition
                        {{ $jenjang === 'SMK' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                        🎓 SMK ({{ \App\Models\CalonSantri::where('jenjang', 'SMK')->count() }})
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <!-- Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    @if($calonSantri->count() > 0)
                        <table class="w-full text-left">
                            <thead class="bg-gray-200 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">No. Daftar</th>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Jenjang</th>
                                    <th class="px-6 py-3">No. Telp</th>
                                    <th class="px-6 py-3">Asal Sekolah</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calonSantri as $santri)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-6 py-3 font-mono font-semibold text-sm">{{ $santri->no_pendaftaran }}</td>
                                        <td class="px-6 py-3">
                                            <div>
                                                <p class="font-semibold">{{ $santri->nama }}</p>
                                                @if($santri->user)
                                                    <p class="text-xs text-gray-500">✓ Akun Terkait</p>
                                                @else
                                                    <p class="text-xs text-yellow-600">⚠ Belum Ada Akun</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-3">
                                            @if($santri->jenjang === 'MTs')
                                                <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                    🏫 MTs
                                                </span>
                                            @else
                                                <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                    🎓 SMK
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 font-mono text-sm">{{ $santri->no_telp }}</td>
                                        <td class="px-6 py-3 text-sm">{{ Str::limit($santri->asal_sekolah, 20) }}</td>
                                        <td class="px-6 py-3">
                                            @if($santri->status === 'lolos')
                                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">✅ Lolos</span>
                                            @elseif($santri->status === 'tidak_lolos')
                                                <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-semibold">❌ Tidak Lolos</span>
                                            @elseif($santri->status === 'proses')
                                                <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">⏳ Proses</span>
                                            @else
                                                <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded text-xs font-semibold">🆕 Baru</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.calon-santri.show', $santri) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                                    👁️
                                                </a>
                                                <a href="{{ route('admin.calon-santri.edit', $santri) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                                    ✏️
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="p-6 bg-gray-50 border-t">
                            {{ $calonSantri->links() }}
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <p>Belum ada calon santri di jenjang {{ $jenjang }}</p>
                            <a href="{{ route('admin.calon-santri.create', ['jenjang' => $jenjang]) }}" class="text-indigo-600 hover:underline font-semibold">
                                Tambah sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
