<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PembayaranExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pembayaran::with('calonSantri', 'records');

        if (!empty($this->filters['search'])) {
            $query->whereHas('calonSantri', function ($q) {
                $q->where('nama', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('no_pendaftaran', 'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('jenjang', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        if (!empty($this->filters['nama'])) {
            $query->whereHas('calonSantri', function ($q) {
                $q->where('nama', 'like', '%' . $this->filters['nama'] . '%');
            });
        }

        if (!empty($this->filters['no_pendaftaran'])) {
            $query->whereHas('calonSantri', function ($q) {
                $q->where('no_pendaftaran', 'like', '%' . $this->filters['no_pendaftaran'] . '%');
            });
        }

        if (!empty($this->filters['jenjang'])) {
            $query->whereHas('calonSantri', function ($q) {
                $q->where('jenjang', 'like', '%' . $this->filters['jenjang'] . '%');
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['due_date_from'])) {
            $query->whereDate('due_date', '>=', $this->filters['due_date_from']);
        }

        if (!empty($this->filters['due_date_to'])) {
            $query->whereDate('due_date', '<=', $this->filters['due_date_to']);
        }

        if (!empty($this->filters['min_total']) && is_numeric($this->filters['min_total'])) {
            $query->where('total_amount', '>=', (float) $this->filters['min_total']);
        }

        if (!empty($this->filters['max_total']) && is_numeric($this->filters['max_total'])) {
            $query->where('total_amount', '<=', (float) $this->filters['max_total']);
        }

        if (!empty($this->filters['min_paid']) && is_numeric($this->filters['min_paid'])) {
            $query->where('paid_amount', '>=', (float) $this->filters['min_paid']);
        }

        if (!empty($this->filters['max_paid']) && is_numeric($this->filters['max_paid'])) {
            $query->where('paid_amount', '<=', (float) $this->filters['max_paid']);
        }

        if (!empty($this->filters['min_remaining']) && is_numeric($this->filters['min_remaining'])) {
            $query->where('remaining_amount', '>=', (float) $this->filters['min_remaining']);
        }

        if (!empty($this->filters['max_remaining']) && is_numeric($this->filters['max_remaining'])) {
            $query->where('remaining_amount', '<=', (float) $this->filters['max_remaining']);
        }

        return $query->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($pembayaran) {
                $history = $pembayaran->records->map(function ($record) {
                    $date = $record->paid_at ? \Illuminate\Support\Carbon::parse($record->paid_at)->translatedFormat('d-m-Y H:i') : '-';

                    return $date . ' - ' . number_format((float) $record->amount, 0, ',', '.') . ' (' . $record->payment_method . ')';
                })->implode(' | ');

                return [
                    'nama' => $pembayaran->calonSantri?->nama ?? '-',
                    'total_pembayaran' => (float) $pembayaran->total_amount,
                    'riwayat_pembayaran' => $history ?: '-',
                    'status_pembayaran' => $this->formatStatus($pembayaran->status),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Total Pembayaran',
            'Riwayat Pembayaran',
            'Status Pembayaran',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 50,
            'D' => 20,
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

        $sheet->getStyle('A2:D' . $sheet->getHighestRow())->applyFromArray([
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

    protected function formatStatus(string $status): string
    {
        return match ($status) {
            'lunas' => 'Lunas',
            'cicilan' => 'Cicilan',
            'belum_bayar' => 'Belum Bayar',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
