<?php

namespace Tests\Feature;

use App\Http\Controllers\PembayaranController;
use App\Models\CalonSantri;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Tests\TestCase;

class PembayaranFilterTest extends TestCase
{
    public function test_index_can_filter_by_name_and_payment_status(): void
    {
        $this->artisan('migrate:fresh', ['--seed' => false]);

        $calonSantri = CalonSantri::create([
            'no_pendaftaran' => 'PSB-2026-00001',
            'nama' => 'Budi Santoso',
            'jenjang' => 'SD',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2018-01-01',
            'alamat' => 'Jl. Contoh No. 1',
            'asal_sekolah' => 'SDN 1',
            'nama_ayah' => 'Ayah Budi',
            'nama_ibu' => 'Ibu Budi',
            'no_telp' => '081234567890',
            'status' => 'baru',
        ]);

        Pembayaran::create([
            'calon_santri_id' => $calonSantri->id,
            'status' => 'lunas',
            'total_amount' => 1000000,
            'paid_amount' => 1000000,
            'remaining_amount' => 0,
        ]);

        $anotherSantri = CalonSantri::create([
            'no_pendaftaran' => 'PSB-2026-00002',
            'nama' => 'Ani Rahma',
            'jenjang' => 'SMP',
            'jenis_kelamin' => 'perempuan',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2015-01-01',
            'alamat' => 'Jl. Contoh No. 2',
            'asal_sekolah' => 'SMPN 2',
            'nama_ayah' => 'Ayah Ani',
            'nama_ibu' => 'Ibu Ani',
            'no_telp' => '081234567891',
            'status' => 'baru',
        ]);

        Pembayaran::create([
            'calon_santri_id' => $anotherSantri->id,
            'status' => 'belum_bayar',
            'total_amount' => 1500000,
            'paid_amount' => 0,
            'remaining_amount' => 1500000,
        ]);

        $request = new Request([
            'nama' => 'Budi',
            'status' => 'lunas',
        ]);

        $response = app(PembayaranController::class)->index($request);

        $this->assertInstanceOf(View::class, $response);
        $this->assertCount(1, $response->getData()['pembayarans']);
        $this->assertSame('Budi Santoso', $response->getData()['pembayarans'][0]->calonSantri->nama);
    }
}
