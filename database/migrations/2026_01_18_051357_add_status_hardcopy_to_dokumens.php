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
        Schema::table('dokumens', function (Blueprint $table) {
            $table->boolean('hardcopy_diterima')->default(false)->after('file_path');
            $table->datetime('tanggal_terima_hardcopy')->nullable()->after('hardcopy_diterima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn(['hardcopy_diterima', 'tanggal_terima_hardcopy']);
        });
    }
};
