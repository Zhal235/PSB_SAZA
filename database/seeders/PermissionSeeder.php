<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Dashboard Permissions
        $permissions = [
            // Dashboard
            ['name' => 'view-dashboard', 'description' => 'View Dashboard', 'category' => 'dashboard'],
            
            // Calon Santri Management
            ['name' => 'view-calon-santri', 'description' => 'View Calon Santri List', 'category' => 'calon-santri'],
            ['name' => 'create-calon-santri', 'description' => 'Create Calon Santri', 'category' => 'calon-santri'],
            ['name' => 'edit-calon-santri', 'description' => 'Edit Calon Santri', 'category' => 'calon-santri'],
            ['name' => 'delete-calon-santri', 'description' => 'Delete Calon Santri', 'category' => 'calon-santri'],
            ['name' => 'export-calon-santri', 'description' => 'Export Calon Santri', 'category' => 'calon-santri'],
            
            // Pembayaran Management
            ['name' => 'view-pembayaran', 'description' => 'View Pembayaran', 'category' => 'pembayaran'],
            ['name' => 'create-pembayaran', 'description' => 'Create Pembayaran', 'category' => 'pembayaran'],
            ['name' => 'verify-pembayaran', 'description' => 'Verify Pembayaran', 'category' => 'pembayaran'],
            
            // Dokumen Management
            ['name' => 'view-dokumen', 'description' => 'View Dokumen', 'category' => 'dokumen'],
            ['name' => 'upload-dokumen', 'description' => 'Upload Dokumen', 'category' => 'dokumen'],
            ['name' => 'verify-dokumen', 'description' => 'Verify Dokumen', 'category' => 'dokumen'],
            
            // Bank Settings
            ['name' => 'view-bank-settings', 'description' => 'View Bank Settings', 'category' => 'settings'],
            ['name' => 'edit-bank-settings', 'description' => 'Edit Bank Settings', 'category' => 'settings'],
            
            // Financial Records
            ['name' => 'view-financial-records', 'description' => 'View Financial Records', 'category' => 'financial'],
            ['name' => 'create-financial-records', 'description' => 'Create Financial Records', 'category' => 'financial'],
            
            // Pembayaran Items
            ['name' => 'view-pembayaran-items', 'description' => 'View Pembayaran Items', 'category' => 'settings'],
            ['name' => 'manage-pembayaran-items', 'description' => 'Manage Pembayaran Items', 'category' => 'settings'],
            
            // User Management
            ['name' => 'view-users', 'description' => 'View Users', 'category' => 'users'],
            ['name' => 'create-users', 'description' => 'Create Users', 'category' => 'users'],
            ['name' => 'edit-users', 'description' => 'Edit Users', 'category' => 'users'],
            ['name' => 'delete-users', 'description' => 'Delete Users', 'category' => 'users'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description'], 'category' => $permission['category']]
            );
        }

        // Assign permissions to roles
        $this->assignRolePermissions();
    }

    private function assignRolePermissions(): void
    {
        $db = \Illuminate\Support\Facades\DB::table('role_permissions');
        
        // Admin has all permissions
        $adminPermissions = Permission::all()->pluck('id')->toArray();
        
        // Santri/Calon Santri
        $santriPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-pembayaran',
            'upload-dokumen',
        ])->pluck('id')->toArray();
        
        // Petugas Pendaftaran
        $petugas_pendaftaranPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-calon-santri',
            'edit-calon-santri',
            'view-dokumen',
            'verify-dokumen',
            'upload-dokumen',
        ])->pluck('id')->toArray();
        
        // Petugas Keuangan
        $petugas_keuanganPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-pembayaran',
            'create-pembayaran',
            'verify-pembayaran',
            'view-financial-records',
            'create-financial-records',
        ])->pluck('id')->toArray();

        // Delete old role permissions
        $db->delete();

        // Admin - all permissions
        foreach ($adminPermissions as $permissionId) {
            $db->insert([
                'role' => 'admin',
                'permission_id' => $permissionId,
            ]);
        }

        // Santri - limited permissions
        foreach ($santriPermissions as $permissionId) {
            $db->insert([
                'role' => 'santri',
                'permission_id' => $permissionId,
            ]);
        }

        // Petugas Pendaftaran - registration permissions
        foreach ($petugas_pendaftaranPermissions as $permissionId) {
            $db->insert([
                'role' => 'petugas_pendaftaran',
                'permission_id' => $permissionId,
            ]);
        }

        // Petugas Keuangan - financial permissions
        foreach ($petugas_keuanganPermissions as $permissionId) {
            $db->insert([
                'role' => 'petugas_keuangan',
                'permission_id' => $permissionId,
            ]);
        }

        echo "✓ Role permissions assigned successfully\n";
    }
}
