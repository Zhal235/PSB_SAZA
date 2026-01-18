<?php

namespace Database\Seeders;

use App\Models\Pekerjaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pekerjaans = [
            'PNS',
            'TNI/Polri',
            'Pegawai Swasta',
            'Wiraswasta',
            'Petani',
            'Nelayan',
            'Pedagang',
            'Pengusaha',
            'Guru',
            'Dosen',
            'Tenaga Medis',
            'Pensiunan',
            'Tidak Bekerja',
            'Mengurus Rumah Tangga',
            'Lainnya'
        ];

        foreach ($pekerjaans as $pekerjaan) {
            Pekerjaan::firstOrCreate(['nama' => $pekerjaan]);
        }
    }
}
