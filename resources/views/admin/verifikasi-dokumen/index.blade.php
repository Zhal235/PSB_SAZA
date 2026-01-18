<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Dokumen - PSB SAZA</title>
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
                <a href="{{ route('admin.calon-santri.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-700 transition">
                    👥 Kelola Pendaftar
                </a>
                <a href="{{ route('verifikasi-dokumen.index') }}" class="block px-4 py-2 rounded bg-indigo-700 hover:bg-indigo-800 transition font-semibold">
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
                <button type="submit" class="w-full px-4 py-2 rounded bg-red-500 hover:bg-red-600 transition font-semibold text-sm">
                    🚪 Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow p-6 sticky top-0 z-10">
                <h2 class="text-2xl font-bold text-gray-800">📋 Verifikasi Dokumen</h2>
            </div>

            <!-- Tabs untuk Jenjang -->
            <div class="bg-white border-b border-gray-200 px-6 sticky top-16 z-10">
                <div class="flex space-x-2">
                    <a href="{{ route('verifikasi-dokumen.index', ['jenjang' => 'MTs']) }}" 
                        class="px-6 py-4 font-semibold border-b-2 transition
                        {{ $jenjang === 'MTs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                        🏫 MTs (Sudah: {{ $mtsSudah }}/{{ $mtsTotal }})
                    </a>
                    <a href="{{ route('verifikasi-dokumen.index', ['jenjang' => 'SMK']) }}" 
                        class="px-6 py-4 font-semibold border-b-2 transition
                        {{ $jenjang === 'SMK' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                        🎓 SMK (Sudah: {{ $smkSudah }}/{{ $smkTotal }})
                    </a>
                </div>
            </div>

            <!-- Filter Hardcopy -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 sticky top-32 z-10">
                <div class="flex space-x-3">
                    <a href="{{ route('verifikasi-dokumen.index', ['jenjang' => $jenjang, 'hardcopy' => 'semua']) }}" 
                        class="px-4 py-2 rounded font-semibold transition {{ $hardcopy === 'semua' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:border-gray-400' }}">
                        📋 Semua
                    </a>
                    <a href="{{ route('verifikasi-dokumen.index', ['jenjang' => $jenjang, 'hardcopy' => 'sudah']) }}" 
                        class="px-4 py-2 rounded font-semibold transition {{ $hardcopy === 'sudah' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:border-gray-400' }}">
                        ✅ Sudah Serah
                    </a>
                    <a href="{{ route('verifikasi-dokumen.index', ['jenjang' => $jenjang, 'hardcopy' => 'belum']) }}" 
                        class="px-4 py-2 rounded font-semibold transition {{ $hardcopy === 'belum' ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:border-gray-400' }}">
                        ⚠️ Belum Serah
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                @if($calonSantri->count() > 0)
                    <div class="bg-white rounded-lg shadow overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700 border-b-2 border-gray-400">
                                    <th class="px-3 py-2 sticky left-0 bg-gray-200 z-10">No. Daftar</th>
                                    <th class="px-3 py-2 sticky left-20 bg-gray-200 z-10">Nama</th>
                                    <th class="px-3 py-2">Sekolah</th>
                                    <th colspan="2" class="px-2 py-2 text-center border-l border-gray-400">Foto</th>
                                    <th colspan="2" class="px-2 py-2 text-center border-l border-gray-400">Ijazah</th>
                                    <th colspan="2" class="px-2 py-2 text-center border-l border-gray-400">Akte</th>
                                    <th colspan="2" class="px-2 py-2 text-center border-l border-gray-400">KTP Ayah</th>
                                    <th colspan="2" class="px-2 py-2 text-center border-l border-gray-400">KTP Ibu</th>
                                    <th colspan="2" class="px-2 py-2 text-center border-l border-gray-400">KK</th>
                                    <th class="px-3 py-2 text-center">Aksi</th>
                                </tr>
                                <tr class="bg-gray-100 text-gray-600 border-b border-gray-300">
                                    <th colspan="3"></th>
                                    <th class="px-2 py-1 text-center border-l border-gray-300 text-xs font-semibold">Up</th>
                                    <th class="px-2 py-1 text-center text-xs font-semibold">HC</th>
                                    <th class="px-2 py-1 text-center border-l border-gray-300 text-xs font-semibold">Up</th>
                                    <th class="px-2 py-1 text-center text-xs font-semibold">HC</th>
                                    <th class="px-2 py-1 text-center border-l border-gray-300 text-xs font-semibold">Up</th>
                                    <th class="px-2 py-1 text-center text-xs font-semibold">HC</th>
                                    <th class="px-2 py-1 text-center border-l border-gray-300 text-xs font-semibold">Up</th>
                                    <th class="px-2 py-1 text-center text-xs font-semibold">HC</th>
                                    <th class="px-2 py-1 text-center border-l border-gray-300 text-xs font-semibold">Up</th>
                                    <th class="px-2 py-1 text-center text-xs font-semibold">HC</th>
                                    <th class="px-2 py-1 text-center border-l border-gray-300 text-xs font-semibold">Up</th>
                                    <th class="px-2 py-1 text-center text-xs font-semibold">HC</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calonSantri as $santri)
                                    @php
                                        $fotoDoc = $santri->dokumens->where('tipe_dokumen', 'Foto')->first();
                                        $ijazahDoc = $santri->dokumens->where('tipe_dokumen', 'Ijazah')->first();
                                        $akteDoc = $santri->dokumens->where('tipe_dokumen', 'Akte Kelahiran')->first();
                                        $ktpAyahDoc = $santri->dokumens->where('tipe_dokumen', 'KTP Ayah')->first();
                                        $ktpIbuDoc = $santri->dokumens->where('tipe_dokumen', 'KTP Ibu')->first();
                                        $kkDoc = $santri->dokumens->where('tipe_dokumen', 'Kartu Keluarga')->first();
                                    @endphp
                                    
                                    <tr class="border-b hover:bg-gray-50 text-xs">
                                        <td class="px-3 py-2 font-bold text-indigo-600 sticky left-0 bg-white z-10">{{ $santri->no_pendaftaran }}</td>
                                        <td class="px-3 py-2 font-semibold sticky left-20 bg-white z-10">{{ substr($santri->nama, 0, 12) }}</td>
                                        <td class="px-3 py-2">{{ substr($santri->asal_sekolah, 0, 10) }}</td>
                                        
                                        <!-- Foto -->
                                        <td class="px-2 py-2 text-center border-l border-gray-300">{{ $fotoDoc ? '✅' : '❌' }}</td>
                                        <td class="px-2 py-2 text-center">
                                            @if($fotoDoc)
                                                <button onclick="toggleDokumenHardcopy({{ $fotoDoc->id }}, {{ $fotoDoc->hardcopy_diterima ? 'true' : 'false' }})" class="px-1 py-0.5 rounded text-xs {{ $fotoDoc->hardcopy_diterima ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} hover:opacity-75">
                                                    {{ $fotoDoc->hardcopy_diterima ? '✅' : '❌' }}
                                                </button>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- Ijazah -->
                                        <td class="px-2 py-2 text-center border-l border-gray-300">{{ $ijazahDoc ? '✅' : '❌' }}</td>
                                        <td class="px-2 py-2 text-center">
                                            @if($ijazahDoc)
                                                <button onclick="toggleDokumenHardcopy({{ $ijazahDoc->id }}, {{ $ijazahDoc->hardcopy_diterima ? 'true' : 'false' }})" class="px-1 py-0.5 rounded text-xs {{ $ijazahDoc->hardcopy_diterima ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} hover:opacity-75">
                                                    {{ $ijazahDoc->hardcopy_diterima ? '✅' : '❌' }}
                                                </button>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- Akte -->
                                        <td class="px-2 py-2 text-center border-l border-gray-300">{{ $akteDoc ? '✅' : '❌' }}</td>
                                        <td class="px-2 py-2 text-center">
                                            @if($akteDoc)
                                                <button onclick="toggleDokumenHardcopy({{ $akteDoc->id }}, {{ $akteDoc->hardcopy_diterima ? 'true' : 'false' }})" class="px-1 py-0.5 rounded text-xs {{ $akteDoc->hardcopy_diterima ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} hover:opacity-75">
                                                    {{ $akteDoc->hardcopy_diterima ? '✅' : '❌' }}
                                                </button>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- KTP Ayah -->
                                        <td class="px-2 py-2 text-center border-l border-gray-300">{{ $ktpAyahDoc ? '✅' : '❌' }}</td>
                                        <td class="px-2 py-2 text-center">
                                            @if($ktpAyahDoc)
                                                <button onclick="toggleDokumenHardcopy({{ $ktpAyahDoc->id }}, {{ $ktpAyahDoc->hardcopy_diterima ? 'true' : 'false' }})" class="px-1 py-0.5 rounded text-xs {{ $ktpAyahDoc->hardcopy_diterima ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} hover:opacity-75">
                                                    {{ $ktpAyahDoc->hardcopy_diterima ? '✅' : '❌' }}
                                                </button>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- KTP Ibu -->
                                        <td class="px-2 py-2 text-center border-l border-gray-300">{{ $ktpIbuDoc ? '✅' : '❌' }}</td>
                                        <td class="px-2 py-2 text-center">
                                            @if($ktpIbuDoc)
                                                <button onclick="toggleDokumenHardcopy({{ $ktpIbuDoc->id }}, {{ $ktpIbuDoc->hardcopy_diterima ? 'true' : 'false' }})" class="px-1 py-0.5 rounded text-xs {{ $ktpIbuDoc->hardcopy_diterima ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} hover:opacity-75">
                                                    {{ $ktpIbuDoc->hardcopy_diterima ? '✅' : '❌' }}
                                                </button>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- KK -->
                                        <td class="px-2 py-2 text-center border-l border-gray-300">{{ $kkDoc ? '✅' : '❌' }}</td>
                                        <td class="px-2 py-2 text-center">
                                            @if($kkDoc)
                                                <button onclick="toggleDokumenHardcopy({{ $kkDoc->id }}, {{ $kkDoc->hardcopy_diterima ? 'true' : 'false' }})" class="px-1 py-0.5 rounded text-xs {{ $kkDoc->hardcopy_diterima ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} hover:opacity-75">
                                                    {{ $kkDoc->hardcopy_diterima ? '✅' : '❌' }}
                                                </button>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Action -->
                                        <td class="px-3 py-2 text-center">
                                            <a href="{{ route('dokumen.create', $santri) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                                📋
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <p class="text-gray-500 text-lg mb-4">Belum ada data calon santri untuk jenjang ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleDokumenHardcopy(dokumenId, currentStatus) {
            const newStatus = !JSON.parse(currentStatus);
            
            fetch(`/api/dokumen/toggle-hardcopy`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    dokumen_id: dokumenId,
                    hardcopy_diterima: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal update status hardcopy');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }
    </script>
</body>
</html>
