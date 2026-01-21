<?php

namespace App\Exports;

use App\Models\CalonSantri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CalonSantriExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $jenjang;

    public function __construct($jenjang = null)
    {
        $this->jenjang = $jenjang;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = CalonSantri::query();
        
        if ($this->jenjang) {
            $query->where('jenjang', $this->jenjang);
        }

        return $query->get()->map(function ($santri, $index) {
            return [
                'no' => $index + 1,
                'no_pendaftaran' => "'" . $santri->no_pendaftaran,  // Force TEXT with apostrophe
                'jenjang' => $santri->jenjang,
                'nama' => $santri->nama,
                'jenis_kelamin' => $santri->jenis_kelamin,
                'nisn' => "'" . $santri->nisn,                      // Force TEXT with apostrophe
                'nik' => "'" . $santri->nik_santri,                 // Force TEXT with apostrophe
                'tempat_lahir' => $santri->tempat_lahir,
                'tanggal_lahir' => $santri->tanggal_lahir->format('d-m-Y'),
                'alamat' => $santri->alamat,
                'desa' => $santri->desa,
                'kecamatan' => $santri->kecamatan,
                'kabupaten' => $santri->kabupaten,
                'provinsi' => $santri->provinsi,
                'kode_pos' => "'" . $santri->kode_pos,              // Force TEXT with apostrophe
                'asal_sekolah' => $santri->asal_sekolah,
                'no_kk' => "'" . $santri->no_kk,                    // Force TEXT with apostrophe
                'nama_ayah' => $santri->nama_ayah,
                'nik_ayah' => "'" . $santri->nik_ayah,              // Force TEXT with apostrophe
                'pendidikan_ayah' => $santri->pendidikan_ayah,
                'pekerjaan_ayah' => $santri->pekerjaan_ayah,
                'hp_ayah' => "'" . $santri->hp_ayah,                // Force TEXT with apostrophe
                'nama_ibu' => $santri->nama_ibu,
                'nik_ibu' => "'" . $santri->nik_ibu,                // Force TEXT with apostrophe
                'pendidikan_ibu' => $santri->pendidikan_ibu,
                'pekerjaan_ibu' => $santri->pekerjaan_ibu,
                'hp_ibu' => "'" . $santri->hp_ibu,                  // Force TEXT with apostrophe
                'no_telp' => "'" . $santri->no_telp,                // Force TEXT with apostrophe
                'hobi' => $santri->hobi,
                'cita_cita' => $santri->cita_cita,
                'jumlah_saudara' => $santri->jumlah_saudara,
                'pendapatan_keluarga' => $santri->pendapatan_keluarga,
                'status' => $santri->status,
                'catatan' => $santri->catatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No.',
            'No. Pendaftaran',
            'Jenjang',
            'Nama Santri',
            'Jenis Kelamin',
            'NISN',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
            'Kode Pos',
            'Asal Sekolah',
            'No. Kartu Keluarga',
            'Nama Ayah',
            'NIK Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'HP Ayah',
            'Nama Ibu',
            'NIK Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'HP Ibu',
            'No. Telepon',
            'Hobi',
            'Cita-cita',
            'Jumlah Saudara',
            'Pendapatan Keluarga',
            'Status',
            'Catatan',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // No
            'B' => 15,   // No. Pendaftaran
            'C' => 10,   // Jenjang
            'D' => 20,   // Nama Santri
            'E' => 15,   // Jenis Kelamin
            'F' => 12,   // NISN
            'G' => 16,   // NIK
            'H' => 15,   // Tempat Lahir
            'I' => 15,   // Tanggal Lahir
            'J' => 25,   // Alamat
            'K' => 15,   // Desa
            'L' => 15,   // Kecamatan
            'M' => 15,   // Kabupaten
            'N' => 15,   // Provinsi
            'O' => 10,   // Kode Pos
            'P' => 20,   // Asal Sekolah
            'Q' => 16,   // No. KK
            'R' => 20,   // Nama Ayah
            'S' => 16,   // NIK Ayah
            'T' => 15,   // Pendidikan Ayah
            'U' => 15,   // Pekerjaan Ayah
            'V' => 15,   // HP Ayah
            'W' => 20,   // Nama Ibu
            'X' => 16,   // NIK Ibu
            'Y' => 15,   // Pendidikan Ibu
            'Z' => 15,   // Pekerjaan Ibu
            'AA' => 15,  // HP Ibu
            'AB' => 15,  // No. Telepon
            'AC' => 15,  // Hobi
            'AD' => 15,  // Cita-cita
            'AE' => 15,  // Jumlah Saudara
            'AF' => 20,  // Pendapatan Keluarga
            'AG' => 12,  // Status
            'AH' => 20,  // Catatan
        ];
    }

    public function styles($sheet)
    {
        // Header styling
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '4C51BF'], // Indigo color
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

        // Data styling
        $sheet->getStyle('A2:AH' . ($sheet->getHighestRow()))->applyFromArray([
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

        // Alternate row colors (zebra striping)
        for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':AH' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'F3F4F6'], // Light gray
                    ],
                ]);
            }
        }

        // Set header height
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Freeze header
        $sheet->freezePane('A2');

        return $sheet;
    }
}
