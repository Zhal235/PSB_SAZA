@extends('layouts.santri')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran & Tagihan')

@section('page-subtitle')
    <p class="text-sm text-gray-600 mt-1">Kelola pembayaran pendaftaran Anda</p>
@endsection

@section('content')
    @if($pembayaran)
        @php
            // Gunakan total dari database (hasil dari itemDetails yang dipilih)
            $totalTagihan = $pembayaran->total_amount;
        @endphp

        <!-- Summary Cards -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Tagihan</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Sudah Dibayar</p>
                <p class="text-2xl font-bold text-green-600 mt-2">
                    Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Sisa Tagihan</p>
                <p class="text-2xl font-bold text-red-600 mt-2">
                    Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-lg font-bold mt-2">
                    @if($pembayaran->paid_amount >= $totalTagihan)
                        <span class="text-green-600">✅ Lunas</span>
                    @elseif($pembayaran->paid_amount > 0)
                        <span class="text-yellow-600">🔄 Cicilan</span>
                    @else
                        <span class="text-red-600">❌ Belum Bayar</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Breakdown Tagihan -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Rincian Tagihan (Item yang Dipilih)</h3>
            
            @if($pembayaran->itemDetails && $pembayaran->itemDetails->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Item</th>
                                <th class="px-4 py-3 text-center">Kategori</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($pembayaran->itemDetails as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-800">{{ $detail->pembayaranItem->nama }}</div>
                                        @if($detail->pembayaranItem->deskripsi)
                                            <p class="text-xs text-gray-600 mt-1">{{ $detail->pembayaranItem->deskripsi }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($detail->pembayaranItem->item_type === 'perlengkapan')
                                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded">📦 Perlengkapan</span>
                                        @else
                                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">💳 Pembayaran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-indigo-600">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="3" class="px-4 py-3 text-right">Total:</td>
                                <td class="px-4 py-3 text-right text-indigo-600 text-lg">
                                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada item yang dipilih. Silakan pilih item di bawah.</p>
                </div>
            @endif

            <!-- Pilih Perlengkapan Optional -->
            @php
                $allActiveItems = \App\Models\PembayaranItem::where('status', 'active')->get();
                $optionalItems = $allActiveItems->filter(function($item) {
                    return $item->item_type === 'perlengkapan' && !$item->is_required;
                });
                $selectedItemIds = $pembayaran->itemDetails->pluck('pembayaran_item_id')->toArray();
            @endphp

            @if($optionalItems->count() > 0)
                    <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded">
                        <h4 class="font-bold text-amber-900 mb-4 flex items-center gap-2">
                            📦 Pilih Perlengkapan (Optional)
                        </h4>
                        
                        <form method="POST" action="{{ route('santri.updateSelectedItems', $pembayaran) }}" class="space-y-3">
                            @csrf
                            @method('PUT')

                            @foreach($optionalItems as $item)
                                <label class="flex items-start gap-3 p-3 bg-white border border-amber-200 rounded cursor-pointer hover:bg-amber-50 transition">
                                    <input type="checkbox" name="items[]" value="{{ $item->id }}" 
                                        class="mt-1" 
                                        {{ in_array($item->id, $selectedItemIds) ? 'checked' : '' }}>
                                    
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">{{ $item->nama }}</p>
                                        @if($item->deskripsi)
                                            <p class="text-xs text-gray-600 mt-1">{{ $item->deskripsi }}</p>
                                        @endif
                                        <p class="text-sm font-bold text-amber-600 mt-2">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach

                            <button type="submit" class="w-full bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 font-semibold transition mt-4">
                                ✅ Simpan Pilihan
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Payment Status Summary -->
                <div class="mt-6 space-y-4">
                    <div class="border border-gray-200 rounded p-4">
                        <p class="text-sm text-gray-600 mb-3">Status Pembayaran Anda</p>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-700 font-semibold">Total Tagihan:</span>
                                <span class="font-semibold text-indigo-600">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b">
                                <span class="text-gray-700">Sudah Dibayar:</span>
                                <span class="font-semibold text-green-600">Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-700 font-semibold">Sisa Tagihan:</span>
                                <span class="font-semibold text-red-600 text-lg">Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($pembayaran->due_date)
                                <div class="flex justify-between items-center text-sm pt-3 border-t">
                                    <span class="text-gray-600">Batas Waktu Pembayaran:</span>
                                    <span class="text-gray-700 font-semibold">{{ $pembayaran->due_date->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Opsi Pembayaran -->
                    @php
                        // Cek apakah sudah ada pembayaran yang verified
                        $verifiedPayment = $pembayaran->records()->where('proof_status', 'verified')->first();
                        // Cek apakah ada pembayaran yang pending
                        $pendingPayment = $pembayaran->records()->where('proof_status', 'pending')->first();
                        // Cek apakah ada pembayaran yang rejected (terakhir)
                        $rejectedPayment = $pembayaran->records()->where('proof_status', 'rejected')->latest()->first();
                    @endphp

                    @if($verifiedPayment)
                        <!-- Tampil jika sudah verified -->
                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">✅</span>
                                <div>
                                    <p class="text-lg font-bold text-green-700">Pembayaran Berhasil Diverifikasi</p>
                                    <p class="text-sm text-green-600">Terima kasih telah melakukan pembayaran</p>
                                </div>
                            </div>
                            <div class="bg-white rounded p-4 space-y-2 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Jumlah Pembayaran:</span>
                                    <span class="font-bold text-green-600">Rp {{ number_format($verifiedPayment->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Tanggal Pembayaran:</span>
                                    <span class="font-semibold text-gray-800">{{ $verifiedPayment->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('santri.pembayaran-invoice', $pembayaran) }}" target="_blank" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold text-sm">
                                📄 Lihat Invoice Lengkap
                            </a>
                        </div>
                    @elseif($pendingPayment)
                        <!-- Tampil jika ada yang masih pending -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">⏳</span>
                                <div>
                                    <p class="text-lg font-bold text-yellow-700">Bukti Pembayaran Sedang Diproses</p>
                                    <p class="text-sm text-yellow-600">Mohon tunggu verifikasi dari admin</p>
                                </div>
                            </div>
                            <div class="bg-white rounded p-4 space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Jumlah:</span>
                                    <span class="font-bold text-yellow-600">Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Tanggal Upload:</span>
                                    <span class="font-semibold text-gray-800">{{ $pendingPayment->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @elseif($rejectedPayment)
                        <!-- Tampil jika ada yang ditolak - bisa upload ulang -->
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-3xl">❌</span>
                                <div>
                                    <p class="text-lg font-bold text-red-700">Bukti Pembayaran Ditolak</p>
                                    <p class="text-sm text-red-600">Silakan upload ulang bukti pembayaran yang benar</p>
                                </div>
                            </div>
                            @if($rejectedPayment->proof_notes)
                                <div class="bg-white rounded p-4 mb-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">📝 Alasan Penolakan:</p>
                                    <p class="text-sm text-gray-800">{{ $rejectedPayment->proof_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    @if(!$verifiedPayment && !$pendingPayment)
                        <!-- Tampil form pembayaran jika belum verified dan tidak ada pending -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                            <p class="text-sm text-blue-700 font-semibold mb-3">
                                💳 Metode Pembayaran Tersedia:
                            </p>

                        <!-- Form Pembayaran -->
                        <form action="{{ route('santri.upload-bukti-pembayaran', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <!-- Pilih Metode Pembayaran -->
                            <div class="bg-white p-4 rounded border border-blue-200">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Metode Pembayaran</label>
                                <div class="space-y-3">
                                    <!-- Cash Option -->
                                    <div class="relative">
                                        <input type="radio" id="payment_cash" name="payment_method" value="cash" class="hidden peer" />
                                        <label for="payment_cash" class="flex cursor-pointer items-start gap-3 rounded border-2 border-gray-200 p-3 peer-checked:border-green-500 peer-checked:bg-green-50">
                                            <span class="text-2xl">💵</span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Pembayaran Tunai (Cash)</p>
                                                <p class="text-xs text-gray-600 mt-1">Bayar langsung ke panitia pendaftaran di sekretariat</p>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Transfer Option -->
                                    <div class="relative">
                                        <input type="radio" id="payment_transfer" name="payment_method" value="transfer" class="hidden peer" />
                                        <label for="payment_transfer" class="flex cursor-pointer items-start gap-3 rounded border-2 border-gray-200 p-3 peer-checked:border-green-500 peer-checked:bg-green-50">
                                            <span class="text-2xl">🏦</span>
                                            <div>
                                                <p class="font-semibold text-gray-800">Transfer Bank</p>
                                                <p class="text-xs text-gray-600 mt-1">Transfer ke rekening sekolah dan unggah bukti pembayaran</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Instruksi Cash (Tidak ada form, hanya instruksi) -->
                            <div id="cash_instruction" class="hidden">
                                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                    <p class="text-sm font-semibold text-green-700 mb-3">📍 Datangi Sekretariat Pendaftaran</p>
                                    <div class="space-y-3">
                                        <div class="bg-white p-3 rounded border border-green-200">
                                            <p class="text-xs text-gray-600 mb-1">Alamat Sekretariat:</p>
                                            <p class="text-sm font-semibold text-gray-800">Pesantren Modern Salsabila Zainia</p>
                                            <p class="text-sm text-gray-700 mt-1">Jl. Raya Sindangbarang-Cidaun Km.18,6</p>
                                            <p class="text-sm text-gray-700">Kp. Cikole Jayapura Cidaun Cianjur</p>
                                        </div>
                                        
                                        <a href="https://maps.app.goo.gl/eRz2tjwrrr6EDrGYA" target="_blank" class="inline-block w-full">
                                            <button type="button" class="w-full bg-blue-600 text-white px-3 py-2 rounded font-semibold hover:bg-blue-700 text-sm">
                                                🗺️ Buka Maps
                                            </button>
                                        </a>

                                        <div class="text-xs text-green-700 space-y-1">
                                            <p><strong>Jam Operasional:</strong> Senin - Jumat (08:00 - 16:00)</p>
                                            <p><strong>Hubungi:</strong> Hubungi panitia untuk jadwal pembayaran</p>
                                            <p><strong>Catatan:</strong> Pastikan membawa bukti pendaftaran saat datang</p>
                                        </div>

                                        <!-- Nominal yang harus dibayar untuk cash -->
                                        <div class="bg-amber-50 border border-amber-200 p-3 rounded mt-3">
                                            <p class="text-xs text-amber-700 font-semibold mb-1">💰 Nominal Pembayaran:</p>
                                            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                                            <p class="text-xs text-blue-700 font-semibold">ℹ️ Pembayaran langsung di sekretariat</p>
                                            <p class="text-xs text-blue-600 mt-1">Tidak perlu submit form online - cukup datang dan bayar langsung ke panitia</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Bukti (Khusus Transfer) -->
                            <div id="bukti_section" class="hidden">
                                <div class="bg-white p-4 rounded border border-green-200 mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">📸 Upload Bukti Pembayaran Transfer *</label>
                                    <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />
                                    <p class="text-xs text-gray-600 mt-2">Format: JPG, PNG, atau PDF (max 5MB)</p>
                                </div>

                                <!-- Nominal yang harus ditransfer -->
                                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded mb-4">
                                    <p class="text-sm text-amber-700 font-semibold mb-2">💰 Nominal yang Harus Ditransfer (Termasuk Kode Unik):</p>
                                    @if($pembayaran->unique_code)
                                        <p class="text-3xl font-bold text-amber-600 mb-3">Rp {{ number_format($totalTagihan - $pembayaran->paid_amount + intval($pembayaran->unique_code), 0, ',', '.') }}</p>
                                        <div class="text-xs text-amber-700 space-y-1">
                                            <p><strong>📊 Rincian:</strong></p>
                                            <p>• Nominal Tagihan: Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}</p>
                                            <p>• Kode Unik: {{ $pembayaran->unique_code }} (gunakan sebagai pembeda)</p>
                                            <p class="mt-2 font-semibold">= Total Transfer: Rp {{ number_format($totalTagihan - $pembayaran->paid_amount + intval($pembayaran->unique_code), 0, ',', '.') }}</p>
                                        </div>
                                    @else
                                        <p class="text-3xl font-bold text-amber-600">Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Hidden input untuk nominal otomatis -->
                            <input type="hidden" name="amount" value="{{ $totalTagihan - $pembayaran->paid_amount }}" />

                            <!-- Submit Button (Hanya untuk Transfer) -->
                            <div id="submit_section" class="hidden bg-white p-4 rounded border border-blue-200">
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700 transition">
                                    ✅ Upload & Konfirmasi Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Bank Accounts for Transfer (Hanya tampil saat pilih Transfer) -->
                    <div id="bank_section" class="hidden">
                        @php
                            $activeBanks = \App\Models\BankSetting::where('is_active', true)->get();
                        @endphp

                        @if($activeBanks->count() > 0)
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-4">
                                <p class="text-sm text-green-700 font-semibold mb-3">
                                    🏦 Rekening Bank untuk Transfer:
                                </p>
                                <div class="space-y-3">
                                    @foreach($activeBanks as $bank)
                                        <div class="bg-white p-3 rounded border border-green-200">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $bank->bank_name }}</p>
                                                    <p class="text-sm text-gray-700 mt-1">
                                                        <span class="font-mono">{{ $bank->account_number }}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-600">A.n. {{ $bank->account_holder }}</p>
                                                    @if($bank->description)
                                                        <p class="text-xs text-gray-500 mt-1 italic">{{ $bank->description }}</p>
                                                    @endif
                                                    @if($bank->phone)
                                                        <p class="text-xs text-gray-600 mt-1">📞 {{ $bank->phone }}</p>
                                                    @endif
                                                </div>
                                                <button type="button" 
                                                    onclick="copyToClipboard('{{ $bank->account_number }}')" 
                                                    class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200">
                                                    📋 Copy
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mb-4">
                                <p class="text-sm text-yellow-700">⚠️ Belum ada rekening bank yang aktif. Hubungi panitia untuk informasi rekening.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-sm text-blue-700 font-semibold mb-2">
                            💡 Informasi Penting:
                        </p>
                        <ul class="text-sm text-blue-700 ml-4 space-y-1 list-disc">
                            <li>Simpan bukti pembayaran untuk verifikasi</li>
                            <li>Setelah transfer, hubungi panitia untuk konfirmasi pembayaran</li>
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Riwayat Pembayaran -->
                <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Riwayat Pembayaran</h3>
            
            @if($pembayaran->records->count() > 0)
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Metode</th>
                            <th class="px-4 py-2 text-center">Kode Unik</th>
                            <th class="px-4 py-2 text-right">Jumlah</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-center">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($pembayaran->records as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-semibold text-gray-700">
                                    {{ $record->paid_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($record->payment_method === 'cash')
                                        💵 Tunai
                                    @elseif($record->payment_method === 'transfer')
                                        🏦 Transfer
                                    @else
                                        📋 Cek
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($record->unique_code)
                                        <span class="font-mono bg-amber-100 text-amber-700 px-2 py-1 rounded">{{ $record->unique_code }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-green-600">
                                    Rp {{ number_format($record->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($record->payment_method === 'transfer')
                                        @if($record->proof_status === 'pending')
                                            <span class="inline-block bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">⏳ Pending</span>
                                        @elseif($record->proof_status === 'verified')
                                            <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">✅ Verified</span>
                                        @else
                                            <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">❌ Rejected</span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">✅ Cash</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($record->proof_image)
                                        <a href="{{ asset('storage/bukti_pembayaran/' . $record->proof_image) }}" target="_blank" class="text-blue-600 hover:underline font-semibold">
                                            📁 Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-center py-6">Belum ada riwayat pembayaran</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex gap-3">
            <a href="{{ route('santri.dashboard') }}" 
                class="flex-1 bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold text-center">
                ← Kembali
            </a>
        </div>
    @else
        @php
            // Check field mana yang belum diisi
            $missingFields = [];
            if (!$calonSantri || empty($calonSantri->nama)) $missingFields[] = 'Nama Lengkap';
            if (!$calonSantri || empty($calonSantri->jenis_kelamin)) $missingFields[] = 'Jenis Kelamin';
            if (!$calonSantri || empty($calonSantri->tempat_lahir)) $missingFields[] = 'Tempat Lahir';
            if (!$calonSantri || empty($calonSantri->tanggal_lahir)) $missingFields[] = 'Tanggal Lahir';
            if (!$calonSantri || empty($calonSantri->alamat)) $missingFields[] = 'Alamat';
            if (!$calonSantri || empty($calonSantri->nama_ayah)) $missingFields[] = 'Nama Ayah';
            if (!$calonSantri || empty($calonSantri->nama_ibu)) $missingFields[] = 'Nama Ibu';
            if (!$calonSantri || empty($calonSantri->hp_ayah)) $missingFields[] = 'HP Ayah';
            if (!$calonSantri || empty($calonSantri->hp_ibu)) $missingFields[] = 'HP Ibu';
        @endphp
        
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded">
            <p class="font-semibold text-lg mb-3">⚠️ Belum Ada Data Pembayaran</p>
            <p class="text-sm mb-4">Silakan lengkapi form pendaftaran terlebih dahulu untuk melihat detail pembayaran Anda.</p>
            
            @if(count($missingFields) > 0)
                <div class="bg-white rounded p-4 mb-4">
                    <p class="text-sm font-semibold text-gray-800 mb-2">📝 Data yang belum dilengkapi:</p>
                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                        @foreach($missingFields as $field)
                            <li>{{ $field }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <a href="{{ route('santri.form-pendaftaran') }}" class="inline-block bg-yellow-600 text-white px-6 py-2 rounded hover:bg-yellow-700 font-semibold mt-2">
                → Lengkapi Form Pendaftaran
            </a>
        </div>
    @endif
@endsection

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Nomor rekening berhasil disalin!');
    }).catch(err => {
        console.error('Error copying: ', err);
    });
}

// Toggle bukti upload section dan cash instruction
document.addEventListener('DOMContentLoaded', function() {
    const paymentCash = document.getElementById('payment_cash');
    const paymentTransfer = document.getElementById('payment_transfer');
    const buktiSection = document.getElementById('bukti_section');
    const cashInstruction = document.getElementById('cash_instruction');
    const submitSection = document.getElementById('submit_section');
    const bankSection = document.getElementById('bank_section');

    const toggleSections = () => {
        if (paymentTransfer && paymentTransfer.checked) {
            buktiSection.classList.remove('hidden');
            cashInstruction.classList.add('hidden');
            submitSection.classList.remove('hidden');
            bankSection.classList.remove('hidden');
        } else if (paymentCash && paymentCash.checked) {
            buktiSection.classList.add('hidden');
            cashInstruction.classList.remove('hidden');
            submitSection.classList.add('hidden');
            bankSection.classList.add('hidden');
        } else {
            buktiSection.classList.add('hidden');
            cashInstruction.classList.add('hidden');
            submitSection.classList.add('hidden');
            bankSection.classList.add('hidden');
        }
    };

    if (paymentCash) {
        paymentCash.addEventListener('change', toggleSections);
    }
    if (paymentTransfer) {
        paymentTransfer.addEventListener('change', toggleSections);
    }
});
</script>
