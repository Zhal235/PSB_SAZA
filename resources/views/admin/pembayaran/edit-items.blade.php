@extends('layouts.admin')

@section('title', 'Pilih Item Pembayaran')
@section('page-title', '📦 Pilih Item Pembayaran')
@section('page-subtitle', '<p class="text-sm text-gray-600 mt-1">{{ $pembayaran->calonSantri->nama }}</p>')

@section('top-bar-action')
    <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="text-gray-600 hover:text-gray-800 font-semibold">← Kembali</a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="POST" action="{{ route('admin.pembayaran.updateItems', $pembayaran) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Info Santri -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">Nama Santri</p>
                <p class="text-lg font-bold text-blue-600">{{ $pembayaran->calonSantri->nama_lengkap }}</p>
            </div>

            <!-- Pembayaran Items -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pilih Item yang Akan Dibeli:</h3>
                
                <div class="grid grid-cols-1 gap-4">
                    @forelse($activeItems as $item)
                        <label class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition">
                            <div class="flex items-start gap-4">
                                <input type="checkbox" name="items[]" value="{{ $item->id }}" 
                                    class="mt-1" 
                                    {{ in_array($item->id, $selectedItemIds) ? 'checked' : '' }}>
                                
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->nama }}</p>
                                            @if($item->deskripsi)
                                                <p class="text-xs text-gray-600 mt-1">{{ $item->deskripsi }}</p>
                                            @endif
                                        </div>
                                        <span class="inline-block px-3 py-1 bg-{{ $item->item_type === 'perlengkapan' ? 'amber' : 'indigo' }}-100 text-{{ $item->item_type === 'perlengkapan' ? 'amber' : 'indigo' }}-700 rounded-full text-xs font-semibold">
                                            @if($item->item_type === 'perlengkapan')
                                                📦 Perlengkapan
                                            @else
                                                💳 Pembayaran
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200">
                                        <div>
                                            @if($item->is_required)
                                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">✓ Wajib</span>
                                            @else
                                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">◯ Optional</span>
                                            @endif
                                            
                                            @if($item->can_cicil)
                                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded ml-2">✓ Cicil {{ $item->cicil_month }} bln</span>
                                            @endif
                                        </div>
                                        <p class="font-bold text-indigo-600 text-lg">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            Belum ada item pembayaran aktif
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Total Summary -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4" id="totalSummary">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Item Terpilih</p>
                        <p class="text-2xl font-bold text-indigo-600" id="itemCount">0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Item</p>
                        <p class="text-2xl font-bold text-green-600" id="totalPrice">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-lg font-bold">
                            @if($pembayaran->paid_amount > 0)
                                <span class="text-yellow-600">🔄 Cicilan</span>
                            @else
                                <span class="text-red-600">🔴 Belum Bayar</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 font-semibold transition">
                    ✅ Simpan
                </button>
                <a href="{{ route('admin.pembayaran.show', $pembayaran) }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 font-semibold transition">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const itemPrices = {
                @foreach($activeItems as $item)
                    {{ $item->id }}: {{ $item->nominal }},
                @endforeach
            };

            function updateTotal() {
                const checkboxes = document.querySelectorAll('input[name="items[]"]:checked');
                const itemCount = checkboxes.length;
                let totalPrice = 0;

                checkboxes.forEach(checkbox => {
                    const itemId = parseInt(checkbox.value);
                    totalPrice += itemPrices[itemId] || 0;
                });

                document.getElementById('itemCount').textContent = itemCount;
                document.getElementById('totalPrice').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
            }

            document.querySelectorAll('input[name="items[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });

            // Initial calculation
            updateTotal();
        </script>
    @endpush
@endsection
