@extends('layouts.admin')

@section('title', 'Detail Bukti Pembayaran')
@section('page-title', '🔍 Detail Bukti Pembayaran')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Verifikasi bukti pembayaran transfer</p>
@endsection

@section('content')
    <div class="max-w-4xl">
        <!-- Info Santri -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">👤 Informasi Santri</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="text-lg font-bold text-gray-800">{{ $bukti->pembayaran->calonSantri->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. Pendaftaran</p>
                    <p class="text-lg font-bold text-gray-800 font-mono">{{ $bukti->pembayaran->calonSantri->no_pendaftaran }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. HP</p>
                    <p class="text-lg font-bold text-gray-800">{{ $bukti->pembayaran->calonSantri->no_telp }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenjang</p>
                    <p class="text-lg font-bold text-gray-800">{{ $bukti->pembayaran->calonSantri->jenjang }}</p>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">💰 Informasi Pembayaran</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Jumlah</p>
                    <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($bukti->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kode Unik</p>
                    <p class="text-3xl font-bold text-amber-600 font-mono">{{ $bukti->unique_code ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Pembayaran</p>
                    <p class="text-lg font-bold text-gray-800">{{ $bukti->paid_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    @if($bukti->proof_status === 'pending')
                        <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded font-semibold">⏳ Pending</span>
                    @elseif($bukti->proof_status === 'verified')
                        <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded font-semibold">✅ Verified</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded font-semibold">❌ Rejected</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        @if($bukti->proof_image)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📸 Bukti Pembayaran</h3>
                <div class="text-center">
                    @if(str_ends_with($bukti->proof_image, '.pdf'))
                        <div class="bg-gray-50 rounded p-8 mb-4">
                            <p class="text-6xl mb-4">📄</p>
                            <p class="text-gray-600 mb-4">File PDF</p>
                            <a href="{{ asset('storage/bukti_pembayaran/' . $bukti->proof_image) }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700 inline-block">
                                📥 Download PDF
                            </a>
                        </div>
                    @else
                        <a href="{{ asset('storage/bukti_pembayaran/' . $bukti->proof_image) }}" target="_blank">
                            <img src="{{ asset('storage/bukti_pembayaran/' . $bukti->proof_image) }}" class="max-w-full rounded border border-gray-300 hover:shadow-lg transition mx-auto" />
                        </a>
                        <p class="text-sm text-gray-600 mt-2">Klik untuk melihat ukuran penuh</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Form Verifikasi -->
        @if($bukti->proof_status === 'pending')
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">✓ Verifikasi Bukti</h3>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="text-sm text-blue-700">
                        <strong>Catatan:</strong> Pastikan kode unik <span class="font-mono font-bold">{{ $bukti->unique_code }}</span> 
                        tertera di belakang nominal transfer sebagai tanda pengenal pembayaran santri ini.
                    </p>
                </div>

                <div class="space-y-4">
                    <!-- Accept Form -->
                    <form action="{{ route('admin.bukti-pembayaran.verify', $bukti) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="approve" />
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded font-semibold hover:bg-green-700 text-lg">
                            ✅ Terima Pembayaran
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <details class="bg-gray-50 rounded p-4">
                        <summary class="cursor-pointer font-semibold text-red-600 hover:text-red-700">❌ Tolak Pembayaran</summary>
                        <form action="{{ route('admin.bukti-pembayaran.verify', $bukti) }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="action" value="reject" />
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan</label>
                                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Jelaskan alasan penolakan bukti pembayaran ini..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700">
                                ❌ Tolak & Kirim Notifikasi
                            </button>
                        </form>
                    </details>
                </div>
            </div>
        @endif

        <!-- History -->
        @if($bukti->proof_notes)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📝 Catatan</h3>
                <p class="text-gray-700">{{ $bukti->proof_notes }}</p>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('admin.bukti-pembayaran.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>
@endsection
