<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SQLite does not support ALTER COLUMN, so we recreate the table.
     */
    public function up(): void
    {
        Schema::create('calon_santris_new', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique();

            // From add_jenjang migration
            $table->string('jenjang')->nullable();

            // Data Santri
            $table->string('nisn')->nullable();
            $table->string('nik_santri')->nullable();
            $table->string('nama');
            $table->string('jenis_kelamin')->nullable();   // was enum NOT NULL
            $table->string('tempat_lahir')->nullable();    // was NOT NULL
            $table->date('tanggal_lahir')->nullable();     // was NOT NULL

            // Alamat Lengkap
            $table->text('alamat')->nullable();            // was NOT NULL
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            $table->string('kode_pos')->nullable();

            // Kelas & Asrama
            $table->string('kelas')->nullable();
            $table->string('asrama')->nullable();

            // Pendidikan & Preferensi
            $table->string('asal_sekolah')->nullable();    // was NOT NULL
            $table->string('hobi')->nullable();
            $table->string('cita_cita')->nullable();
            $table->integer('jumlah_saudara')->nullable();

            // Data Keluarga
            $table->string('no_kk')->nullable();
            $table->string('pendapatan_keluarga')->nullable(); // from add_pendapatan_keluarga

            // Data Ayah
            $table->string('nama_ayah')->nullable();       // was NOT NULL
            $table->string('nik_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('hp_ayah')->nullable();

            // Data Ibu
            $table->string('nama_ibu')->nullable();        // was NOT NULL
            $table->string('nik_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('hp_ibu')->nullable();

            // Kontak & Status
            $table->string('no_telp')->nullable();         // was NOT NULL
            $table->string('phone_type')->nullable();      // from add_phone_type
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete(); // from add_phone_type
            $table->string('status')->default('baru');
            $table->boolean('status_hardcopy')->default(false); // from add_hardcopy
            $table->datetime('tanggal_serah_hardcopy')->nullable(); // from add_hardcopy
            $table->text('catatan')->nullable();

            $table->timestamps();
        });

        // Copy all existing data by column name
        DB::statement('
            INSERT INTO calon_santris_new (
                id, no_pendaftaran, jenjang,
                nisn, nik_santri, nama, jenis_kelamin, tempat_lahir, tanggal_lahir,
                alamat, provinsi, kabupaten, kecamatan, desa, kode_pos,
                kelas, asrama,
                asal_sekolah, hobi, cita_cita, jumlah_saudara,
                no_kk, pendapatan_keluarga,
                nama_ayah, nik_ayah, pendidikan_ayah, pekerjaan_ayah, hp_ayah,
                nama_ibu, nik_ibu, pendidikan_ibu, pekerjaan_ibu, hp_ibu,
                no_telp, phone_type, user_id,
                status, status_hardcopy, tanggal_serah_hardcopy, catatan,
                created_at, updated_at
            )
            SELECT
                id, no_pendaftaran, jenjang,
                nisn, nik_santri, nama, jenis_kelamin, tempat_lahir, tanggal_lahir,
                alamat, provinsi, kabupaten, kecamatan, desa, kode_pos,
                kelas, asrama,
                asal_sekolah, hobi, cita_cita, jumlah_saudara,
                no_kk, pendapatan_keluarga,
                nama_ayah, nik_ayah, pendidikan_ayah, pekerjaan_ayah, hp_ayah,
                nama_ibu, nik_ibu, pendidikan_ibu, pekerjaan_ibu, hp_ibu,
                no_telp, phone_type, user_id,
                status, status_hardcopy, tanggal_serah_hardcopy, catatan,
                created_at, updated_at
            FROM calon_santris
        ');

        Schema::drop('calon_santris');
        Schema::rename('calon_santris_new', 'calon_santris');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot easily reverse without data loss
    }
};
