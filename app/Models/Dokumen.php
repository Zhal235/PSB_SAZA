<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'calon_santri_id',
        'tipe_dokumen',
        'file_path',
        'hardcopy_diterima',
        'tanggal_terima_hardcopy'
    ];

    public function calonSantri()
    {
        return $this->belongsTo(CalonSantri::class);
    }
}
