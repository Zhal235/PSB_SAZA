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
        Schema::create('pembayaran_items', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Pendaftaran, SPP, Seragam, dll
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 12, 2); // Harga item
            $table->boolean('is_required')->default(true); // Wajib atau optional
            $table->boolean('can_cicil')->default(false); // Bisa cicil atau tidak
            $table->integer('cicil_month')->nullable(); // Maksimal berapa bulan cicil
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_items');
    }
};
