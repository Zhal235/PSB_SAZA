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
        Schema::create('pembayaran_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->enum('payment_method', ['cash', 'transfer', 'check'])->default('cash');
            $table->decimal('amount', 12, 2); // Jumlah yang dibayar
            $table->dateTime('paid_at'); // Tanggal pembayaran
            $table->text('notes')->nullable(); // Catatan
            $table->string('receipt_number')->nullable(); // Nomor kwitansi
            $table->timestamps();
            $table->index('pembayaran_id');
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_records');
    }
};
