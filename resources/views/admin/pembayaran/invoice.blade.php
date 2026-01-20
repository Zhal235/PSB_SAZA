<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - PSB SAZA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: white;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .invoice {
            border: 1px solid #ddd;
            padding: 30px;
            background: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
        }
        .header-left h1 {
            color: #4f46e5;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header-left p {
            color: #666;
            font-size: 12px;
        }
        .invoice-title {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .invoice-number {
            text-align: right;
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
        .invoice-date {
            text-align: right;
            color: #666;
            font-size: 12px;
            margin-top: 3px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section div {
            width: 48%;
        }
        .info-label {
            font-weight: bold;
            color: #4f46e5;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-content {
            color: #333;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background: #4f46e5;
            color: white;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        table tbody tr:hover {
            background: #f9f5ff;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .total-box {
            width: 350px;
            border: 2px solid #4f46e5;
            padding: 15px;
            background: #f0f4ff;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .total-row.highlight {
            border-top: 1px solid #4f46e5;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #4f46e5;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }
        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }
        .status-cicilan {
            background: #fef3c7;
            color: #92400e;
        }
        .status-belum {
            background: #fee2e2;
            color: #991b1b;
        }
        .notes {
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #4f46e5;
            margin-bottom: 20px;
            font-size: 12px;
            color: #555;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        .print-button {
            text-align: right;
            margin-bottom: 20px;
        }
        .print-button button {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                background: white;
            }
            .invoice {
                border: none;
                box-shadow: none;
            }
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="print-button">
            <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        </div>

        <div class="invoice">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1>PSB SAZA</h1>
                    <p>Pendaftaran Santri Aza</p>
                    <p style="margin-top: 5px; font-size: 11px;">Jl. Contoh No. 123, Bandung</p>
                </div>
                <div>
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">No. INV-{{ $pembayaran->id }}-{{ now()->format('mYd') }}</div>
                    <div class="invoice-date">Tanggal: {{ now()->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <div>
                    <div class="info-label">📋 Data Calon Santri</div>
                    <div class="info-content">
                        <p><strong>{{ $pembayaran->calonSantri->nama }}</strong></p>
                        <p>No. Pendaftaran: {{ $pembayaran->calonSantri->no_pendaftaran }}</p>
                        <p>Jenjang: {{ $pembayaran->calonSantri->jenjang }}</p>
                        <p>Alamat: {{ $pembayaran->calonSantri->alamat }}</p>
                        <p>No. Telepon: {{ $pembayaran->calonSantri->no_telp ?? '-' }}</p>
                    </div>
                </div>
                <div>
                    <div class="info-label">🎯 Rincian Tagihan</div>
                    <div class="info-content">
                        <p><strong>Jatuh Tempo:</strong> {{ $pembayaran->due_date ? $pembayaran->due_date->format('d/m/Y') : '-' }}</p>
                        <div style="margin-top: 10px;">
                            @if($pembayaran->status === 'lunas')
                                <span class="status-badge status-lunas">✅ LUNAS</span>
                            @elseif($pembayaran->status === 'cicilan')
                                <span class="status-badge status-cicilan">🔄 CICILAN</span>
                            @else
                                <span class="status-badge status-belum">❌ BELUM BAYAR</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tagihan Pembayaran PSB SAZA Tahun {{ now()->format('Y') }}</td>
                        <td class="text-right"><strong>Rp {{ number_format($pembayaran->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Total Section -->
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

            <!-- Payment History -->
            @if($pembayaran->records->count() > 0)
                <div class="notes">
                    <strong>📋 Riwayat Pembayaran:</strong>
                    <div style="margin-top: 10px;">
                        @foreach($pembayaran->records as $record)
                            <p>
                                {{ $record->paid_at->format('d/m/Y') }} - Rp {{ number_format($record->amount, 0, ',', '.') }} 
                                ({{ ucfirst($record->payment_method) }})
                                @if($record->receipt_number)
                                    - Kwitansi: {{ $record->receipt_number }}
                                @endif
                            </p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Payment Instructions -->
            <div class="notes">
                <strong>💳 Petunjuk Pembayaran:</strong>
                <p style="margin-top: 10px;">
                    1. Transfer ke rekening PSB SAZA: BCA 1234567890 (Atas Nama Pesantren SAZA)<br>
                    2. Atau bayar langsung ke kantor bagian keuangan<br>
                    3. Setelah pembayaran, mohon kirimkan bukti transfer ke WhatsApp admin<br>
                    4. Invoice ini berlaku sebagai bukti tagihan
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Invoice ini dihasilkan secara otomatis oleh sistem PSB SAZA</p>
                <p>{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
