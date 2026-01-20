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
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date'); // Tanggal transaksi
            $table->enum('type', ['income', 'expense'])->default('income'); // Pemasukan atau Pengeluaran
            $table->string('category'); // Kategori (Pendaftaran, Donasi, SPP, Operasional, dll)
            $table->decimal('amount', 15, 2); // Jumlah uang
            $table->enum('payment_method', ['cash', 'transfer']); // Metode pembayaran
            $table->string('reference_number')->nullable(); // Nomor referensi/kwitansi
            $table->text('description')->nullable(); // Deskripsi/Catatan
            $table->string('recorded_by')->nullable(); // Dicatat oleh siapa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
