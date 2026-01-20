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
        Schema::table('users', function (Blueprint $table) {
            $table->string('jenjang')->nullable()->after('phone'); // MTs, SMK, or null
            $table->boolean('has_selected_jenjang')->default(false)->after('jenjang'); // Flag untuk tracking
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jenjang', 'has_selected_jenjang']);
        });
    }
};
