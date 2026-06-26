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
        Schema::table('pembayaran_items', function (Blueprint $table) {
            $table->decimal('nominal_old', 12, 2)->nullable()->after('nominal')->comment('Harga lama sebelum perubahan');
            $table->date('effective_date')->nullable()->after('nominal_old')->comment('Tanggal efektif harga baru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_items', function (Blueprint $table) {
            $table->dropColumn('nominal_old');
            $table->dropColumn('effective_date');
        });
    }
};
