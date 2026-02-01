<?php

namespace Database\Seeders;

use App\Models\CalonSantri;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalonSantriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Calon Santri 1
        CalonSantri::create([
            'no_pendaftaran' => 'PSB-2026-00001',
            'jenjang' => 'MTs',
            'nisn' => '1234567890',
            'nik_santri' => '3507140512060001',
            'nama' => 'Ahmad Rizki Pratama',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2012-05-12',
            'alamat' => 'Jl. Merdeka No. 123',
            'provinsi' => 'Jawa Barat',
            'kabupaten' => 'Bandung',
            'kecamatan' => 'Coblong',
            'desa' => 'Citarum',
            'kode_pos' => '40142',
            'kelas' => null,
            'asrama' => null,
            'asal_sekolah' => 'SD Negeri 1 Bandung',
            'hobi' => 'Sepak bola',
            'cita_cita' => 'Insinyur',
            'jumlah_saudara' => 2,
            'no_kk' => '3507140512060000',
            'pendapatan_keluarga' => 'Rp 2.000.000 - Rp 5.000.000',
            'nama_ayah' => 'Rizki Wibowo',
            'nik_ayah' => '3507125012060001',
            'pendidikan_ayah' => 'S1 (Sarjana)',
            'pekerjaan_ayah' => 'Guru',
            'hp_ayah' => '081234567890',
            'nama_ibu' => 'Siti Nurhaliza',
            'nik_ibu' => '3507145012060002',
            'pendidikan_ibu' => 'SMA',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'hp_ibu' => '082345678901',
            'no_telp' => '085234567890',
            'phone_type' => 'ayah',
            'user_id' => null,
            'status' => 'baru',
            'status_hardcopy' => 0,
        ]);

        // Calon Santri 2
        CalonSantri::create([
            'no_pendaftaran' => 'PSB-2026-00002',
            'jenjang' => 'SMK',
            'nisn' => '1234567891',
            'nik_santri' => '3507140612060002',
            'nama' => 'Nur Azizah Putri',
            'jenis_kelamin' => 'perempuan',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2011-08-20',
            'alamat' => 'Jl. Ahmad Yani No. 45',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Selatan',
            'kecamatan' => 'Kebayoran Baru',
            'desa' => 'Pondok Indah',
            'kode_pos' => '12310',
            'kelas' => null,
            'asrama' => null,
            'asal_sekolah' => 'SMP Negeri 5 Jakarta',
            'hobi' => 'Membaca',
            'cita_cita' => 'Dokter',
            'jumlah_saudara' => 1,
            'no_kk' => '3507140612060000',
            'pendapatan_keluarga' => 'Rp 5.000.000 - Rp 10.000.000',
            'nama_ayah' => 'Aziz Rahman',
            'nik_ayah' => '3507125012060003',
            'pendidikan_ayah' => 'S2 (Magister)',
            'pekerjaan_ayah' => 'Dokter',
            'hp_ayah' => '081298765432',
            'nama_ibu' => 'Dwi Lestari',
            'nik_ibu' => '3507145012060004',
            'pendidikan_ibu' => 'S1 (Sarjana)',
            'pekerjaan_ibu' => 'Guru',
            'hp_ibu' => '082198765432',
            'no_telp' => '085198765432',
            'phone_type' => 'ayah',
            'user_id' => null,
            'status' => 'baru',
            'status_hardcopy' => 0,
        ]);

        // Calon Santri 3
        CalonSantri::create([
            'no_pendaftaran' => 'PSB-2026-00003',
            'jenjang' => 'MTs',
            'nisn' => '1234567892',
            'nik_santri' => '3507140712060003',
            'nama' => 'Muhammad Hafid Ridho',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2012-11-15',
            'alamat' => 'Jl. Diponegoro No. 78',
            'provinsi' => 'Jawa Timur',
            'kabupaten' => 'Surabaya',
            'kecamatan' => 'Genteng',
            'desa' => 'Genteng',
            'kode_pos' => '60275',
            'kelas' => null,
            'asrama' => null,
            'asal_sekolah' => 'SD Negeri 2 Surabaya',
            'hobi' => 'Bulu tangkis',
            'cita_cita' => 'Arsitek',
            'jumlah_saudara' => 3,
            'no_kk' => '3507140712060000',
            'pendapatan_keluarga' => 'Kurang dari Rp 500.000',
            'nama_ayah' => 'Hafid Muaddin',
            'nik_ayah' => '3507125012060005',
            'pendidikan_ayah' => 'SMA',
            'pekerjaan_ayah' => 'Wiraswasta',
            'hp_ayah' => '081567890123',
            'nama_ibu' => 'Fatimah Nurrochmah',
            'nik_ibu' => '3507145012060006',
            'pendidikan_ibu' => 'SMP',
            'pekerjaan_ibu' => 'Wiraswasta',
            'hp_ibu' => '082567890123',
            'no_telp' => '085567890123',
            'phone_type' => 'ayah',
            'user_id' => null,
            'status' => 'baru',
            'status_hardcopy' => 0,
        ]);
    }
}
