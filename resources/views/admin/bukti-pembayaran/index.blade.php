@extends('layouts.admin')

@section('title', 'Verifikasi Bukti Pembayaran')
@section('page-title', '✓ Verifikasi Bukti Pembayaran Transfer')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Kelola bukti pembayaran transfer dari santri</p>
@endsection

@section('content')
    <!-- Status Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b">
            <a href="{{ route('admin.bukti-pembayaran.index', ['status' => 'pending']) }}" 
                class="flex-1 px-6 py-3 text-center font-semibold {{ $status === 'pending' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}">
                ⏳ Pending ({{ \App\Models\PembayaranRecord::where('payment_method', 'transfer')->where('proof_status', 'pending')->count() }})
            </a>
            <a href="{{ route('admin.bukti-pembayaran.index', ['status' => 'verified']) }}" 
                class="flex-1 px-6 py-3 text-center font-semibold {{ $status === 'verified' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-600' }}">
                ✅ Verified ({{ \App\Models\PembayaranRecord::where('payment_method', 'transfer')->where('proof_status', 'verified')->count() }})
            </a>
            <a href="{{ route('admin.bukti-pembayaran.index', ['status' => 'rejected']) }}" 
                class="flex-1 px-6 py-3 text-center font-semibold {{ $status === 'rejected' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-600' }}">
                ❌ Rejected ({{ \App\Models\PembayaranRecord::where('payment_method', 'transfer')->where('proof_status', 'rejected')->count() }})
            </a>
        </div>
    </div>

    @if($bukti->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama Santri</th>
                        <th class="px-4 py-3 text-left">No. Pendaftaran</th>
                        <th class="px-4 py-3 text-left">No. HP</th>
                        <th class="px-4 py-3 text-center">Kode Unik</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($bukti as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $item->pembayaran->calonSantri->nama }}</td>
                            <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $item->pembayaran->calonSantri->no_pendaftaran }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $item->pembayaran->calonSantri->no_telp }}</td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-amber-600">{{ $item->unique_code }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-indigo-600">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $item->paid_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($item->proof_status === 'pending')
                                    <span class="inline-block bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">⏳ Pending</span>
                                @elseif($item->proof_status === 'verified')
                                    <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">✅ Verified</span>
                                @else
                                    <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">❌ Rejected</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" onclick="openModal('modal-{{ $item->id }}')" class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-blue-700">
                                    👁️ Lihat
                                </button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div id="modal-{{ $item->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                                <!-- Modal Header -->
                                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 flex justify-between items-start sticky top-0">
                                    <div>
                                        <h3 class="text-xl font-bold">{{ $item->pembayaran->calonSantri->nama }}</h3>
                                        <p class="text-sm text-blue-100 mt-1">No. Pendaftaran: {{ $item->pembayaran->calonSantri->no_pendaftaran }}</p>
                                    </div>
                                    <button type="button" onclick="closeModal('modal-{{ $item->id }}')" class="text-white text-2xl font-bold hover:text-blue-200">
                                        ✕
                                    </button>
                                </div>

                                <!-- Modal Content -->
                                <div class="p-6 space-y-6">
                                    <!-- Info Pembayaran -->
                                    <div class="bg-gray-50 rounded p-4">
                                        <h4 class="font-semibold text-gray-800 mb-3">💰 Informasi Pembayaran</h4>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-600">Nominal</p>
                                                <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($item->amount, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Kode Unik</p>
                                                <p class="text-lg font-bold text-amber-600 font-mono">{{ $item->unique_code }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Tanggal Upload</p>
                                                <p class="font-semibold text-gray-800">{{ $item->paid_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Status</p>
                                                @if($item->proof_status === 'pending')
                                                    <span class="inline-block bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">⏳ Pending</span>
                                                @elseif($item->proof_status === 'verified')
                                                    <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">✅ Verified</span>
                                                @else
                                                    <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">❌ Rejected</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bukti Pembayaran -->
                                    @if($item->proof_image)
                                        <div>
                                            <h4 class="font-semibold text-gray-800 mb-3">📸 Bukti Pembayaran</h4>
                                            @if(str_ends_with($item->proof_image, '.pdf'))
                                                <div class="bg-red-50 border-2 border-red-300 rounded p-6 text-center">
                                                    <p class="text-5xl mb-2">📄</p>
                                                    <p class="text-sm text-red-600 font-semibold mb-3">File PDF</p>
                                                    <a href="{{ asset('storage/bukti_pembayaran/' . $item->proof_image) }}" target="_blank" class="inline-block bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700">
                                                        📥 Download PDF
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-center bg-gray-50 rounded p-4">
                                                    <img src="{{ asset('storage/bukti_pembayaran/' . $item->proof_image) }}" class="max-w-full rounded border border-gray-300 mx-auto" />
                                                    <a href="{{ asset('storage/bukti_pembayaran/' . $item->proof_image) }}" target="_blank" class="inline-block mt-3 text-blue-600 hover:underline text-sm">
                                                        Buka ukuran penuh
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Catatan -->
                                    @if($item->proof_notes)
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                            <p class="text-sm text-blue-700"><strong>Catatan Admin:</strong> {{ $item->proof_notes }}</p>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    @if($item->proof_status === 'pending')
                                        <div class="border-t pt-6 flex gap-3">
                                            <form action="{{ route('admin.bukti-pembayaran.verify', $item) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="action" value="approve" />
                                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded font-semibold hover:bg-green-700">
                                                    ✅ Terima Pembayaran
                                                </button>
                                            </form>
                                            <button type="button" onclick="showRejectForm('modal-{{ $item->id }}')" class="flex-1 bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700">
                                                ❌ Tolak Pembayaran
                                            </button>
                                        </div>

                                        <!-- Reject Form -->
                                        <div id="reject-form-{{ $item->id }}" class="hidden border-t pt-6">
                                            <form action="{{ route('admin.bukti-pembayaran.verify', $item) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="reject" />
                                                <div class="mb-4">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan</label>
                                                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                                </div>
                                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700">
                                                    Kirim Penolakan
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bukti->hasPages())
            <div class="mt-6">
                {{ $bukti->links() }}
            </div>
        @endif
    @else
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-500 text-lg">📭 Tidak ada bukti pembayaran {{ $status }}</p>
        </div>
    @endif

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function showRejectForm(modalId) {
            const rejectFormId = 'reject-form-' + modalId.split('-')[1];
            document.getElementById(rejectFormId).classList.toggle('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        });
    </script>
@endsection
