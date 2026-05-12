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
        Schema::create('pembayaran_item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->foreignId('pembayaran_item_id')->constrained('pembayaran_items')->onDelete('cascade');
            $table->integer('quantity')->default(1); // Jumlah yang dipilih
            $table->decimal('unit_price', 12, 2); // Harga satuan saat dipilih
            $table->decimal('subtotal', 12, 2); // Total untuk item ini
            $table->timestamps();
            
            // Unique index untuk prevent duplicate item
            $table->unique(['pembayaran_id', 'pembayaran_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_item_details');
    }
};
