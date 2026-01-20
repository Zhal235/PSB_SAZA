<?php

namespace Database\Seeders;

use App\Models\PembayaranItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PembayaranItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'nama' => 'Biaya Pendaftaran',
                'deskripsi' => 'Biaya pendaftaran calon santri',
                'nominal' => 500000,
                'is_required' => true,
                'can_cicil' => false,
                'cicil_month' => null,
                'status' => 'active',
            ],
            [
                'nama' => 'Formulir & Tes Masuk',
                'deskripsi' => 'Biaya formulir dan tes masuk',
                'nominal' => 200000,
                'is_required' => true,
                'can_cicil' => false,
                'cicil_month' => null,
                'status' => 'active',
            ],
            [
                'nama' => 'Seragam Sekolah',
                'deskripsi' => 'Paket seragam (3 set)',
                'nominal' => 450000,
                'is_required' => true,
                'can_cicil' => true,
                'cicil_month' => 3,
                'status' => 'active',
            ],
            [
                'nama' => 'Perlengkapan Sekolah',
                'deskripsi' => 'Buku, alat tulis, dan perlengkapan lainnya',
                'nominal' => 300000,
                'is_required' => true,
                'can_cicil' => true,
                'cicil_month' => 3,
                'status' => 'active',
            ],
            [
                'nama' => 'SPP Bulan 1',
                'deskripsi' => 'SPP bulan pertama',
                'nominal' => 400000,
                'is_required' => true,
                'can_cicil' => false,
                'cicil_month' => null,
                'status' => 'active',
            ],
            [
                'nama' => 'Asuransi Kesehatan',
                'deskripsi' => 'Asuransi kesehatan 1 tahun',
                'nominal' => 250000,
                'is_required' => false,
                'can_cicil' => false,
                'cicil_month' => null,
                'status' => 'active',
            ],
            [
                'nama' => 'Kegiatan & Ekskul',
                'deskripsi' => 'Dana kegiatan dan ekstrakurikuler',
                'nominal' => 200000,
                'is_required' => false,
                'can_cicil' => true,
                'cicil_month' => 6,
                'status' => 'active',
            ],
        ];

        foreach ($items as $item) {
            PembayaranItem::create($item);
        }
    }
}

