<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - {{ $calonSantri->nama }}</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1F2937;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #6B7280;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            background: #10B981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            background: #F3F4F6;
            padding: 12px 15px;
            border-left: 4px solid #4F46E5;
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

        .mono {
            font-family: 'Courier New', monospace;
            background: #F9FAFB;
            padding: 2px 6px;
            border-radius: 4px;
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
            color: #4F46E5;
        }

        .total-section {
            background: #F0F4FF;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
            border: 2px solid #E0E7FF;
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
            border-top: 2px solid #C7D2FE;
            padding-top: 10px;
            margin-top: 10px;
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

        .code-box {
            background: #FEF08A;
            border: 2px solid #FCD34D;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
        }

        .code-box .label {
            font-size: 12px;
            color: #92400E;
            margin-bottom: 5px;
        }

        .code-box .code {
            font-size: 24px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #B45309;
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

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .print-button button:hover {
            background: #4338CA;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button no-print">
            <button onclick="window.print()">🖨️ Cetak / Print</button>
        </div>

        <div class="header">
            <h1>✓ BUKTI PENDAFTARAN</h1>
            <p>Pesantren Modern Salsabila Zainia</p>
            <span class="badge">{{ strtoupper($calonSantri->status) }}</span>
        </div>

        <!-- Info Santri -->
        <div class="section">
            <div class="section-title">👤 DATA SANTRI</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nomor Pendaftaran</span>
                    <span class="info-value mono">{{ $calonSantri->no_pendaftaran }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $calonSantri->nama }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">{{ ucfirst($calonSantri->jenis_kelamin) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Lahir</span>
                    <span class="info-value">{{ $calonSantri->tanggal_lahir->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenjang</span>
                    <span class="info-value">{{ $calonSantri->jenjang }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor HP</span>
                    <span class="info-value">{{ $calonSantri->no_telp }}</span>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="section">
            <div class="section-title">💰 RINGKASAN PEMBAYARAN</div>
            
            @php
                $totalTagihan = $items ? $items->sum('nominal') : $pembayaran->total_amount;
            @endphp

            @if($items && $items->count() > 0)
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
                    <span>Sisa Pembayaran:</span>
                    <span style="color: {{ $totalTagihan - $pembayaran->paid_amount > 0 ? '#DC2626' : '#10B981' }}">
                        Rp {{ number_format($totalTagihan - $pembayaran->paid_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Lokasi Pembayaran -->
        <div class="section">
            <div class="section-title">📍 LOKASI PEMBAYARAN / SEKRETARIAT</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Institusi</span>
                    <span class="info-value">Pesantren Modern Salsabila Zainia</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Alamat</span>
                    <span class="info-value">Jl. Raya Sindangbarang-Cidaun Km.18,6<br>Kp. Cikole Jayapura Cidaun Cianjur</span>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="notes">
            <strong>⚠️ CATATAN PENTING:</strong>
            <ul style="margin-left: 20px;">
                <li>Simpan bukti pendaftaran ini dengan baik</li>
                <li>Bawa bukti ini saat datang ke sekretariat untuk pembayaran</li>
                <li>Hubungi panitia untuk informasi lebih lanjut tentang jadwal pembayaran</li>
            </ul>
        </div>

        <div class="footer">
            <p>Dokumen ini dicetak pada: <strong>{{ now()->format('d/m/Y H:i:s') }}</strong></p>
            <p style="margin-top: 10px; font-size: 11px;">Pesantren Modern Salsabila Zainia © 2026</p>
        </div>
    </div>
</body>
</html>
