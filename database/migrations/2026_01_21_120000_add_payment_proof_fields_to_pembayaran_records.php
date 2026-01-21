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
        Schema::table('pembayaran_records', function (Blueprint $table) {
            // Upload bukti pembayaran (khusus transfer)
            $table->string('proof_image')->nullable()->after('notes');
            $table->enum('proof_status', ['pending', 'verified', 'rejected'])->default('pending')->after('proof_image');
            $table->text('proof_notes')->nullable()->after('proof_status');
            
            // Kode unik untuk transfer
            $table->string('unique_code')->nullable()->unique()->after('proof_notes');
        });

        // Juga tambahkan ke pembayarans table untuk tracking
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('unique_code')->nullable()->unique()->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_records', function (Blueprint $table) {
            $table->dropColumn(['proof_image', 'proof_status', 'proof_notes', 'unique_code']);
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });
    }
};
