<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - PSB Pesantren Modern Salsabiila Zainia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f4f6; color: #333; line-height: 1.6; }
        .container { max-width: 860px; margin: 0 auto; padding: 20px; }
        .invoice { border: 1px solid #ddd; padding: 30px; background: white; }
        .kop-image { width: 65%; display: block; margin: 0 auto 8px auto; }
        .kop-divider { border: none; border-top: 2px solid #333; margin-bottom: 6px; }
        .invoice-subtitle { text-align: center; font-size: 15px; font-weight: bold; color: #333; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #4f46e5; padding-bottom: 12px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .invoice-title { text-align: right; font-size: 22px; font-weight: bold; color: #4f46e5; }
        .invoice-number, .invoice-date { text-align: right; color: #666; font-size: 12px; margin-top: 3px; }
        .info-section { display: flex; justify-content: space-between; margin-bottom: 25px; gap: 20px; }
        .info-section > div { width: 48%; }
        .info-label { font-weight: bold; color: #4f46e5; font-size: 12px; text-transform: uppercase; margin-bottom: 6px; }
        .info-content { color: #333; font-size: 13px; }
        .info-content p { margin-bottom: 3px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; white-space: nowrap; margin-top: 8px; }
        .status-lunas  { background: #d1fae5; color: #065f46; }
        .status-cicilan { background: #fef3c7; color: #92400e; }
        .status-belum  { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead { background: #4f46e5; color: white; }
        table th { padding: 10px 12px; text-align: left; font-size: 12px; }
        table td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        .total-section { display: flex; justify-content: flex-end; margin-bottom: 25px; }
        .total-box { width: 340px; border: 2px solid #4f46e5; padding: 15px; background: #f0f4ff; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .total-row.highlight { border-top: 1px solid #4f46e5; padding-top: 10px; margin-top: 8px; font-weight: bold; font-size: 15px; color: #4f46e5; }
        .notes { background: #f5f5f5; padding: 12px 15px; border-left: 4px solid #4f46e5; margin-bottom: 15px; font-size: 12px; color: #555; }
        .notes p { margin-top: 4px; }
        .footer { margin-top: 25px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 11px; }
        .print-button { text-align: right; margin-bottom: 15px; }
        .print-button button { background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: bold; }
        @media print { .print-button { display: none; } body { background: white; } .invoice { border: none; } }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button">
            <button onclick="window.print()">&#128438; Cetak / Simpan PDF</button>
        </div>
        <div class="invoice">

            @if(isset($kopImagePath))
                <img src="{{ $kopImagePath }}" class="kop-image" alt="Kop Surat">
                <hr class="kop-divider">
            @endif

            <div class="invoice-subtitle">Pendaftaran Santri Baru</div>

            <div class="header">
                <div></div>
                <div>
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">No. INV-{{ $pembayaran->id }}-{{ now()->format('mYd') }}</div>
                    <div class="invoice-date">Tanggal: {{ now()->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="info-section">
                <div>
                    <div class="info-label">&#128203; Data Calon Santri</div>
                    <div class="info-content">
                        <p><strong>{{ $pembayaran->calonSantri->nama }}</strong></p>
                        <p>No. Pendaftaran: {{ $pembayaran->calonSantri->no_pendaftaran }}</p>
                        <p>Jenjang: {{ $pembayaran->calonSantri->jenjang }}</p>
                        <p>Alamat: {{ $pembayaran->calonSantri->alamat }}</p>
                        <p>No. Telepon: {{ $pembayaran->calonSantri->no_telp ?? '-' }}</p>
                    </div>
                </div>
                <div>
                    <div class="info-label">&#127919; Rincian Tagihan</div>
                    <div class="info-content">
                        <p><strong>Jatuh Tempo:</strong> {{ $pembayaran->due_date ? $pembayaran->due_date->format('d/m/Y') : '-' }}</p>
                        <p>
                            @if($pembayaran->status === 'lunas')
                                <span class="status-badge status-lunas">&#10003; LUNAS</span>
                            @elseif($pembayaran->status === 'cicilan')
                                <span class="status-badge status-cicilan">~ CICILAN</span>
                            @else
                                <span class="status-badge status-belum">&#10007; BELUM BAYAR</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($items) && $items->count() > 0)
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $item->nama }}
                                    @if($item->deskripsi)
                                        <br><small style="color: #888;">{{ $item->deskripsi }}</small>
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>1</td>
                            <td>Tagihan Pembayaran PSB Pesantren Modern Salsabiila Zainia Tahun {{ now()->format('Y') }}</td>
                            <td class="text-right">Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-box">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row">
                        <span>Sudah Dibayar:</span>
                        <span style="color: green;">- Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row highlight">
                        <span>Sisa Tagihan:</span>
                        <span style="color: {{ $pembayaran->remaining_amount > 0 ? 'red' : 'green' }};">Rp {{ number_format($pembayaran->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($pembayaran->records->count() > 0)
                <div class="notes">
                    <strong>&#128203; Riwayat Pembayaran:</strong>
                    <div style="margin-top: 6px;">
                        @foreach($pembayaran->records as $record)
                            <p>{{ $record->paid_at->format('d/m/Y') }} - Rp {{ number_format($record->amount, 0, ',', '.') }} ({{ ucfirst($record->payment_method) }})@if($record->receipt_number) - Kwitansi: {{ $record->receipt_number }}@endif</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="notes">
                <strong>&#128179; Petunjuk Pembayaran:</strong>
                <div style="margin-top: 6px;">
                    @if(isset($bankSettings) && $bankSettings->count() > 0)
                        @foreach($bankSettings as $bank)
                            <p>{{ $loop->iteration }}. Transfer ke rekening: {{ $bank->bank_name }} {{ $bank->account_number }} (Atas Nama {{ $bank->account_holder }})@if($bank->description) - {{ $bank->description }}@endif</p>
                        @endforeach
                        <p>{{ $bankSettings->count() + 1 }}. Atau bayar langsung ke kantor bagian keuangan</p>
                        @php $firstBank = $bankSettings->first(); @endphp
                        @if($firstBank && $firstBank->phone)
                            <p>{{ $bankSettings->count() + 2 }}. Setelah pembayaran, kirimkan bukti transfer ke WhatsApp admin: {{ $firstBank->phone }}</p>
                        @else
                            <p>{{ $bankSettings->count() + 2 }}. Setelah pembayaran, kirimkan bukti transfer ke WhatsApp admin</p>
                        @endif
                        <p>{{ $bankSettings->count() + 3 }}. Invoice ini berlaku sebagai bukti tagihan</p>
                    @else
                        <p>1. Bayar langsung ke kantor bagian keuangan</p>
                        <p>2. Setelah pembayaran, kirimkan bukti transfer ke WhatsApp admin</p>
                        <p>3. Invoice ini berlaku sebagai bukti tagihan</p>
                    @endif
                </div>
            </div>

            <div class="footer">
                <p>Invoice ini dihasilkan secara otomatis oleh sistem PSB Pesantren Modern Salsabiila Zainia</p>
                <p>{{ now()->format('d/m/Y H:i') }}</p>
            </div>

        </div>
    </div>
</body>
</html>
