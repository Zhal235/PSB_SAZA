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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_santri_id')->constrained('calon_santris')->onDelete('cascade');
            $table->enum('status', ['belum_bayar', 'cicilan', 'lunas'])->default('belum_bayar');
            $table->decimal('total_amount', 12, 2)->default(0); // Total yang harus dibayar
            $table->decimal('paid_amount', 12, 2)->default(0); // Total yang sudah dibayar
            $table->decimal('remaining_amount', 12, 2)->default(0); // Sisa
            $table->date('due_date')->nullable(); // Deadline pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
