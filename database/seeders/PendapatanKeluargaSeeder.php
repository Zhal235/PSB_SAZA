<?php

namespace Database\Seeders;

use App\Models\PendapatanKeluarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendapatanKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendapatans = [
            'Kurang dari Rp 500.000',
            'Rp 500.000 - Rp 1.000.000',
            'Rp 1.000.000 - Rp 2.000.000',
            'Rp 2.000.000 - Rp 3.000.000',
            'Rp 3.000.000 - Rp 5.000.000',
            'Rp 5.000.000 - Rp 10.000.000',
            'Lebih dari Rp 10.000.000'
        ];

        foreach ($pendapatans as $pendapatan) {
            PendapatanKeluarga::firstOrCreate(['nama' => $pendapatan]);
        }
    }
}
