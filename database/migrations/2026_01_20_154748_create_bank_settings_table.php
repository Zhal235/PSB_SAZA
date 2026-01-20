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
        Schema::create('bank_settings', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name'); // Nama Bank (BCA, Mandiri, BNI, etc)
            $table->string('account_number'); // Nomor Rekening
            $table->string('account_holder'); // Nama Pemilik Rekening
            $table->text('description')->nullable(); // Deskripsi/Catatan
            $table->string('phone')->nullable(); // Nomor HP Admin Bank
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_settings');
    }
};
