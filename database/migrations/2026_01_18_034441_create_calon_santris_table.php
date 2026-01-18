<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calon_santris', function (Blueprint $table) {
            $table->id();
            
            // No Pendaftaran (auto-generated)
            $table->string('no_pendaftaran')->unique();
            
            // Data Santri
            $table->string('nisn')->nullable();
            $table->string('nik_santri')->nullable();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            
            // Alamat Lengkap
            $table->text('alamat');
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            $table->string('kode_pos')->nullable();
            
            // Kelas & Asrama (hidden/auto-filled)
            $table->string('kelas')->nullable();
            $table->string('asrama')->nullable();
            
            // Pendidikan & Preferensi
            $table->string('asal_sekolah');
            $table->string('hobi')->nullable();
            $table->string('cita_cita')->nullable();
            $table->integer('jumlah_saudara')->nullable();
            
            // Data Keluarga
            $table->string('no_kk')->nullable();
            
            // Data Ayah
            $table->string('nama_ayah');
            $table->string('nik_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('hp_ayah')->nullable();
            
            // Data Ibu
            $table->string('nama_ibu');
            $table->string('nik_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('hp_ibu')->nullable();
            
            // Kontak & Status
            $table->string('no_telp');
            $table->enum('status', ['baru', 'proses', 'lolos', 'tidak_lolos'])->default('baru');
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_santris');
    }
};
