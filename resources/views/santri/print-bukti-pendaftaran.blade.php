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
            background-color: #f0f1f3;
            padding: 15px;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #4F46E5;
            padding: 15px 30px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #f5f7ff 0%, #ffffff 100%);
            border-radius: 8px;
            margin: -30px -30px 20px -30px;
            padding: 20px 30px;
        }

        .header h1 {
            color: #1F2937;
            font-size: 22px;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .header p {
            color: #6B7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 8px;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: linear-gradient(135deg, #F3F4F6 0%, #F9FAFB 100%);
            padding: 10px 12px;
            border-left: 5px solid #4F46E5;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 12px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-radius: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
            background: #FAFBFC;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 10px;
            color: #64748B;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 13px;
            color: #1F2937;
            font-weight: 500;
            line-height: 1.4;
        }

        .mono {
            font-family: 'Courier New', monospace;
            background: #F0F4FF;
            padding: 2px 4px;
            border-radius: 3px;
            color: #4F46E5;
            font-weight: 600;
            letter-spacing: 0.3px;
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
            background: white;
        }

        .table th {
            background: #F0F4FF;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #1F2937;
            border: 1px solid #E5E7EB;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .table td {
            padding: 10px;
            border: 1px solid #E5E7EB;
            color: #4B5563;
        }

        .table tr:nth-child(even) {
            background: #FAFBFC;
        }

        .amount {
            text-align: right;
            font-weight: 600;
            color: #4F46E5;
        }

        .total-section {
            background: linear-gradient(135deg, #F0F4FF 0%, #F8FAFC 100%);
            padding: 15px;
            border-radius: 6px;
            margin-top: 12px;
            border: 2px solid #E0E7FF;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #E0E7FF;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
            border-top: 2px solid #C7D2FE;
            border-bottom: none;
            padding-top: 6px;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .notes {
            background: #FEF3C7;
            border-left: 5px solid #F59E0B;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            margin-bottom: 15px;
            font-size: 11px;
            color: #78350F;
        }

        .notes strong {
            display: block;
            margin-bottom: 6px;
            color: #92400E;
            font-size: 12px;
        }

        .notes ul {
            margin: 0;
            padding-left: 18px;
        }

        .notes li {
            margin-bottom: 3px;
            line-height: 1.3;
        }

        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            line-height: 1.5;
        }

        .footer p {
            margin: 0;
        }

        .code-box {
            background: #FEF08A;
            border: 2px solid #FCD34D;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin: 15px 0;
            page-break-inside: avoid;
        }

        .code-box .label {
            font-size: 10px;
            color: #92400E;
            margin-bottom: 3px;
        }

        .code-box .code {
            font-size: 20px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #B45309;
        }

        @media print {
            * {
                margin: 0;
                padding: 0;
            }
            html, body {
                width: 100%;
                height: 100%;
            }
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .container {
                box-shadow: none;
                padding: 20px;
                max-width: 100%;
                border-radius: 0;
                margin: 0;
                page-break-inside: avoid;
                page-break-after: avoid;
            }
            .no-print {
                display: none !important;
            }
            .header {
                margin: 0 0 15px 0 !important;
                padding: 15px 0 !important;
                page-break-inside: avoid;
            }
            .section {
                page-break-inside: avoid;
            }
            .print-button {
                display: none;
            }
            .table {
                page-break-inside: avoid;
            }
        }

        .print-button {
            text-align: center;
            margin-bottom: 15px;
        }

        .print-button button {
            background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }

        .print-button button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button no-print">
            <button onclick="window.print()">Cetak / Print</button>
        </div>

        <div class="header">
            <h1>BUKTI PENDAFTARAN</h1>
            <p>Pesantren Modern Salsabila Zainia</p>
            <span class="badge">{{ strtoupper($calonSantri->status) }}</span>
        </div>

        <!-- Info Santri -->
        <div class="section">
            <div class="section-title">DATA SANTRI</div>
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
                    <span class="info-value">{{ $calonSantri->jenis_kelamin ? ucfirst($calonSantri->jenis_kelamin) : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Lahir</span>
                    <span class="info-value">
                        @if($calonSantri->tanggal_lahir)
                            {{ $calonSantri->tanggal_lahir->format('d F Y') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jenjang Pendaftaran</span>
                    <span class="info-value">{{ $calonSantri->jenjang ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor Telepon</span>
                    <span class="info-value">{{ $calonSantri->no_telp ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="section">
            <div class="section-title">RINGKASAN PEMBAYARAN</div>
            
            @php
                $totalTagihan = $pembayaran->total_amount;
            @endphp

            @if($pembayaran && $pembayaran->itemDetails && $pembayaran->itemDetails->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item Pembayaran</th>
                            <th style="text-align: right;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayaran->itemDetails as $detail)
                            <tr>
                                <td>{{ $detail->pembayaranItem->nama }}</td>
                                <td class="amount">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
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
            <div class="section-title">LOKASI PEMBAYARAN / SEKRETARIAT</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nama Institusi</span>
                    <span class="info-value">Pesantren Modern Salsabila Zainia</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Alamat Lengkap</span>
                    <span class="info-value">Jl. Raya Sindangbarang-Cidaun Km.18,6, Kp. Cikole, Jayapura, Cidaun, Cianjur</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jam Operasional</span>
                    <span class="info-value">Senin - Jumat: 08:00 - 16:00</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Hubungi</span>
                    <span class="info-value">Hubungi panitia untuk jadwal pembayaran</span>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="notes">
            <strong>CATATAN PENTING:</strong>
            <ul>
                <li>Simpan bukti pendaftaran ini dengan baik untuk arsip pribadi</li>
                <li>Bawa bukti ini saat datang ke sekretariat untuk pembayaran</li>
                <li>Hubungi panitia untuk informasi lebih lanjut tentang jadwal pembayaran</li>
                <li>Pastikan melakukan pembayaran sesuai dengan nominal dan batas waktu yang ditentukan</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Dokumen ini dicetak pada:</strong></p>
            <p>{{ now()->format('l, d F Y · H:i:s') }}</p>
            <p style="margin-top: 12px; font-size: 11px;">Pesantren Modern Salsabila Zainia © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
