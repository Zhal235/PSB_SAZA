<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonSantri extends Model
{
    protected $fillable = [
        'no_pendaftaran',
        'jenjang',
        'nisn',
        'nik_santri',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'kode_pos',
        'kelas',
        'asrama',
        'asal_sekolah',
        'hobi',
        'cita_cita',
        'jumlah_saudara',
        'no_kk',
        'pendapatan_keluarga',
        'nama_ayah',
        'nik_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'hp_ayah',
        'nama_ibu',
        'nik_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'hp_ibu',
        'no_telp',
        'status',
        'status_hardcopy',
        'tanggal_serah_hardcopy',
        'catatan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    /**
     * Generate nomor pendaftaran otomatis
     * Format: PSB-YYYY-NNNNN (PSB-2026-00001)
     */
    public static function generateNoPendaftaran()
    {
        $tahun = date('Y');
        $lastEntry = self::whereYear('created_at', $tahun)
            ->orderBy('id', 'desc')
            ->first();
        
        $nomor = $lastEntry ? intval(substr($lastEntry->no_pendaftaran, -5)) + 1 : 1;
        
        return 'PSB-' . $tahun . '-' . str_pad($nomor, 5, '0', STR_PAD_LEFT);
    }
}
