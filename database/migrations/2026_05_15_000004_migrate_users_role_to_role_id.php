<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Map role strings to role_id
        $roleMapping = [
            'admin' => 1,
            'petugas_pendaftaran' => 2,
            'petugas_keuangan' => 3,
            'calon_santri' => 4,
            'santri' => 5,
        ];

        // Update users with role_id based on role string
        foreach ($roleMapping as $roleName => $roleId) {
            DB::table('users')
                ->where('role', $roleName)
                ->update(['role_id' => $roleId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is one-way, reverting would require keeping the role string
    }
};
