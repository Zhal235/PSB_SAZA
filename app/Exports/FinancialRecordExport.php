<?php

namespace App\Exports;

use App\Models\FinancialRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class FinancialRecordExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = FinancialRecord::query();

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        if (!empty($this->filters['payment_method'])) {
            $query->where('payment_method', $this->filters['payment_method']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('transaction_date', 'desc')
            ->get()
            ->map(function (FinancialRecord $record) {
                return [
                    'transaction_date' => $record->transaction_date?->format('d/m/Y') ?? '-',
                    'type' => $record->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    'category' => $record->category ?? '-',
                    'amount' => (float) $record->amount,
                    'payment_method' => $record->payment_method === 'cash' ? 'Cash' : 'Transfer',
                    'reference_number' => $record->reference_number ?? '-',
                    'description' => $record->description ?? '-',
                    'recorded_by' => $record->recorded_by ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Jenis',
            'Kategori',
            'Jumlah',
            'Metode Pembayaran',
            'Ref/Kwitansi',
            'Deskripsi',
            'Dicatat Oleh',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 16,
            'C' => 22,
            'D' => 18,
            'E' => 20,
            'F' => 20,
            'G' => 40,
            'H' => 20,
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A2:H' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ]);

        return $sheet;
    }
}
