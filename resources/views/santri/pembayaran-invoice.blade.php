<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $pembayaran->calonSantri->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #00a0a0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-left h1 {
            color: #1F2937;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header-left p {
            color: #6B7280;
            font-size: 14px;
        }

        .header-right {
            text-align: right;
        }

        .invoice-number {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 5px;
        }

        .invoice-number strong {
            color: #1F2937;
        }

        .invoice-date {
            font-size: 14px;
            color: #6B7280;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            background: #F3F4F6;
            padding: 12px 15px;
            border-left: 4px solid #00a0a0;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #6B7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 15px;
            color: #1F2937;
            font-weight: 500;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 13px;
        }

        .table th {
            background: #F3F4F6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #1F2937;
            border-bottom: 2px solid #E5E7EB;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
            color: #4B5563;
        }

        .table tr:last-child td {
            border-bottom: 2px solid #E5E7EB;
        }

        .amount {
            text-align: right;
            font-weight: 600;
            color: #00a0a0;
        }

        .total-section {
            background: #F0FFFE;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
            border: 2px solid #D1FAF8;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            border-top: 2px solid #B3EAE6;
            padding-top: 10px;
            margin-top: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-paid {
            background: #DCFCE7;
            color: #166534;
        }

        .status-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-partial {
            background: #FCD34D;
            color: #78350F;
        }

        .notes {
            background: #FFFBEB;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 12px;
            color: #92400E;
        }

        .notes strong {
            display: block;
            margin-bottom: 5px;
            color: #B45309;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 12px;
            color: #6B7280;
        }

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            background: #00a0a0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .print-button button:hover {
            background: #008080;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button no-print">
            <button onclick="window.print()">🖨️ Cetak / Print Invoice</button>
        </div>

        <div class="header">
            <div class="header-left">
                <h1>INVOICE PEMBAYARAN</h1>
                <p>Pesantren Modern Salsabila Zainia</p>
            </div>
            <div class="header-right">
                <div class="invoice-number">
                    No. Invoice: <strong>{{ $pembayaran->id }}</strong>
                </div>
                <div class="invoice-date">
                    Tanggal: <strong>{{ now()->format('d/m/Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Data Santri -->
        <div class="section">
            <div class="section-title">👤 DATA CALON SANTRI</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $pembayaran->calonSantri->nama }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">No. Pendaftaran</span>
                    <span class="info-value">{{ $pembayaran->calonSantri->no_pendaftaran }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenjang</span>
                    <span class="info-value">{{ $pembayaran->calonSantri->jenjang }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor HP</span>
                    <span class="info-value">{{ $pembayaran->calonSantri->no_telp }}</span>
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class="section">
            <div class="section-title">💰 DETAIL PEMBAYARAN</div>
            
            @php
                $totalTagihan = \App\Models\PembayaranItem::where('status', 'active')->sum('nominal');
                $items = \App\Models\PembayaranItem::where('status', 'active')->get();
            @endphp

            @if($items->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item Pembayaran</th>
                            <th style="text-align: right;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td class="amount">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="total-section">
                <div class="total-row">
                    <span>Total Tagihan:</span>
                    <span>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Sudah Dibayar:</span>
                    <span>Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row grand-total">
                    <span>Sisa Tagihan:</span>
                    <span style="color: {{ $totalTagihan - $pembayaran->paid_amount > 0 ? '#DC2626' : '#10B981' }}">
                        Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}
                    </span>
                </div>
                <div style="text-align: right; margin-top: 15px;">
                    @php
                        if ($pembayaran->paid_amount >= $totalTagihan) {
                            $status = 'Lunas';
                            $statusClass = 'status-paid';
                        } elseif ($pembayaran->paid_amount > 0) {
                            $status = 'Cicilan';
                            $statusClass = 'status-partial';
                        } else {
                            $status = 'Belum Dibayar';
                            $statusClass = 'status-pending';
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $status }}</span>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        @if($pembayaran->records->count() > 0)
            <div class="section">
                <div class="section-title">📋 RIWAYAT PEMBAYARAN</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Metode</th>
                            <th>Kode Unik</th>
                            <th style="text-align: right;">Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaran->records as $record)
                            <tr>
                                <td>{{ $record->paid_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($record->payment_method === 'cash')
                                        💵 Tunai
                                    @else
                                        🏦 Transfer
                                    @endif
                                </td>
                                <td>{{ $record->unique_code ?? '-' }}</td>
                                <td class="amount">Rp {{ number_format($record->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($record->payment_method === 'transfer')
                                        @if($record->proof_status === 'verified')
                                            <span class="status-badge status-paid">✅ Verified</span>
                                        @elseif($record->proof_status === 'pending')
                                            <span class="status-badge status-pending">⏳ Pending</span>
                                        @else
                                            <span class="status-badge" style="background: #FEE2E2; color: #991B1B;">❌ Rejected</span>
                                        @endif
                                    @else
                                        <span class="status-badge status-paid">✅ Tercatat</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Catatan -->
        <div class="notes">
            <strong>ℹ️ INFORMASI PENTING:</strong>
            <ul style="margin-left: 20px;">
                <li>Simpan invoice ini untuk referensi pembayaran Anda</li>
                <li>Hubungi panitia untuk pertanyaan lebih lanjut</li>
                <li>Invoice ini sah untuk digunakan sebagai bukti pembayaran</li>
            </ul>
        </div>

        <div class="footer">
            <p>Invoice ini dicetak pada: <strong>{{ now()->format('d/m/Y H:i:s') }}</strong></p>
            <p style="margin-top: 10px; font-size: 11px;">Pesantren Modern Salsabila Zainia © 2026</p>
        </div>
    </div>
</body>
</html>
