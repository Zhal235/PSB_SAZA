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
        Schema::table('calon_santris', function (Blueprint $table) {
            $table->boolean('status_hardcopy')->default(false)->after('status');
            $table->datetime('tanggal_serah_hardcopy')->nullable()->after('status_hardcopy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_santris', function (Blueprint $table) {
            $table->dropColumn(['status_hardcopy', 'tanggal_serah_hardcopy']);
        });
    }
};
