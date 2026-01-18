<?php

namespace Database\Seeders;

use App\Models\Pendidikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikans = [
            'Tidak Sekolah',
            'SD/MI',
            'SMP/MTs',
            'SMA/SMK/MA',
            'Diploma (D1/D2/D3)',
            'S1 (Sarjana)',
            'S2 (Magister)',
            'S3 (Doktor)'
        ];

        foreach ($pendidikans as $pendidikan) {
            Pendidikan::firstOrCreate(['nama' => $pendidikan]);
        }
    }
}
