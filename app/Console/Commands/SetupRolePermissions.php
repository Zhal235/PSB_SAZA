<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetupRolePermissions extends Command
{
    protected $signature = 'role:setup-permissions';
    protected $description = 'Setup role permissions';

    public function handle()
    {
        // Admin - All permissions
        $this->assignRolePermissions('admin', Permission::all()->pluck('id')->toArray());
        
        // Calon Santri & Santri
        $this->assignRolePermissions('calon_santri', Permission::whereIn('name', [
            'view-dashboard',
            'view-pembayaran',
            'upload-dokumen',
        ])->pluck('id')->toArray());
        
        $this->assignRolePermissions('santri', Permission::whereIn('name', [
            'view-dashboard',
            'view-pembayaran',
            'upload-dokumen',
        ])->pluck('id')->toArray());
        
        // Petugas Pendaftaran
        $this->assignRolePermissions('petugas_pendaftaran', Permission::whereIn('name', [
            'view-dashboard',
            'view-calon-santri',
            'create-calon-santri',
            'edit-calon-santri',
            'view-dokumen',
            'verify-dokumen',
            'upload-dokumen',
        ])->pluck('id')->toArray());
        
        // Petugas Keuangan
        $this->assignRolePermissions('petugas_keuangan', Permission::whereIn('name', [
            'view-dashboard',
            'view-pembayaran',
            'create-pembayaran',
            'verify-pembayaran',
            'view-financial-records',
            'create-financial-records',
        ])->pluck('id')->toArray());
        
        $this->info('Role permissions setup completed!');
    }

    private function assignRolePermissions($role, $permissionIds)
    {
        // Clear existing permissions for this role
        DB::table('role_permissions')->where('role', $role)->delete();
        
        // Assign new permissions
        foreach ($permissionIds as $permissionId) {
            DB::table('role_permissions')->insert([
                'role' => $role,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->line("✓ Permissions assigned to role: {$role}");
    }
}