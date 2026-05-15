<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions by category
        $permissionsData = [
            'Dashboard' => [
                ['name' => 'view_dashboard', 'display_name' => 'View Dashboard'],
            ],
            'Calon Santri Management' => [
                ['name' => 'view_calon_santri', 'display_name' => 'View Calon Santri List'],
                ['name' => 'create_calon_santri', 'display_name' => 'Create Calon Santri'],
                ['name' => 'edit_calon_santri', 'display_name' => 'Edit Calon Santri'],
                ['name' => 'delete_calon_santri', 'display_name' => 'Delete Calon Santri'],
                ['name' => 'export_calon_santri', 'display_name' => 'Export Calon Santri'],
            ],
            'Pembayaran Management' => [
                ['name' => 'view_pembayaran', 'display_name' => 'View Pembayaran'],
                ['name' => 'create_pembayaran', 'display_name' => 'Create Pembayaran'],
                ['name' => 'edit_pembayaran', 'display_name' => 'Edit Pembayaran'],
                ['name' => 'delete_pembayaran', 'display_name' => 'Delete Pembayaran'],
                ['name' => 'verify_pembayaran', 'display_name' => 'Verify Pembayaran'],
            ],
            'Pembayaran Items Management' => [
                ['name' => 'view_pembayaran_items', 'display_name' => 'View Pembayaran Items'],
                ['name' => 'create_pembayaran_items', 'display_name' => 'Create Pembayaran Items'],
                ['name' => 'edit_pembayaran_items', 'display_name' => 'Edit Pembayaran Items'],
                ['name' => 'delete_pembayaran_items', 'display_name' => 'Delete Pembayaran Items'],
            ],
            'Dokumen Verification' => [
                ['name' => 'view_dokumen', 'display_name' => 'View Dokumen'],
                ['name' => 'verify_dokumen', 'display_name' => 'Verify Dokumen'],
                ['name' => 'manage_hardcopy', 'display_name' => 'Manage Hardcopy Status'],
            ],
            'Bank Settings' => [
                ['name' => 'view_bank_settings', 'display_name' => 'View Bank Settings'],
                ['name' => 'edit_bank_settings', 'display_name' => 'Edit Bank Settings'],
            ],
            'Financial Records' => [
                ['name' => 'view_financial_records', 'display_name' => 'View Financial Records'],
                ['name' => 'create_financial_records', 'display_name' => 'Create Financial Records'],
                ['name' => 'edit_financial_records', 'display_name' => 'Edit Financial Records'],
                ['name' => 'delete_financial_records', 'display_name' => 'Delete Financial Records'],
            ],
            'User Management' => [
                ['name' => 'view_users', 'display_name' => 'View Users'],
                ['name' => 'create_users', 'display_name' => 'Create Users'],
                ['name' => 'edit_users', 'display_name' => 'Edit Users'],
                ['name' => 'delete_users', 'display_name' => 'Delete Users'],
                ['name' => 'manage_user_roles', 'display_name' => 'Manage User Roles'],
            ],
            'Reports' => [
                ['name' => 'view_reports', 'display_name' => 'View Reports'],
                ['name' => 'export_reports', 'display_name' => 'Export Reports'],
            ],
        ];

        // Create all permissions
        $permissions = [];
        foreach ($permissionsData as $category => $perms) {
            foreach ($perms as $perm) {
                $permission = Permission::firstOrCreate(
                    ['name' => $perm['name']],
                    [
                        'display_name' => $perm['display_name'],
                        'category' => $category,
                    ]
                );
                $permissions[$perm['name']] = $permission->id;
            }
        }

        // Define roles with their permissions
        $rolesData = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Akses penuh ke semua fitur aplikasi',
                'permissions' => array_keys($permissions), // Admin get all permissions
            ],
            [
                'name' => 'petugas_pendaftaran',
                'display_name' => 'Petugas Pendaftaran',
                'description' => 'Mengelola calon santri dan pendaftaran',
                'permissions' => [
                    'view_dashboard',
                    'view_calon_santri',
                    'create_calon_santri',
                    'edit_calon_santri',
                    'export_calon_santri',
                    'view_dokumen',
                    'verify_dokumen',
                    'manage_hardcopy',
                ],
            ],
            [
                'name' => 'petugas_keuangan',
                'display_name' => 'Petugas Keuangan',
                'description' => 'Mengelola pembayaran dan keuangan',
                'permissions' => [
                    'view_dashboard',
                    'view_pembayaran',
                    'create_pembayaran',
                    'edit_pembayaran',
                    'verify_pembayaran',
                    'view_pembayaran_items',
                    'view_calon_santri',
                    'view_bank_settings',
                    'view_financial_records',
                    'create_financial_records',
                    'view_reports',
                    'export_reports',
                ],
            ],
            [
                'name' => 'calon_santri',
                'display_name' => 'Calon Santri',
                'description' => 'Calon santri yang sedang mendaftar',
                'permissions' => [
                    'view_dashboard',
                ],
            ],
            [
                'name' => 'santri',
                'display_name' => 'Santri',
                'description' => 'Santri yang sudah diterima',
                'permissions' => [
                    'view_dashboard',
                ],
            ],
        ];

        // Create roles and assign permissions
        foreach ($rolesData as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                ]
            );

            // Sync permissions
            $permissionIds = [];
            foreach ($roleData['permissions'] as $permName) {
                if (isset($permissions[$permName])) {
                    $permissionIds[] = $permissions[$permName];
                }
            }
            $role->permissions()->sync($permissionIds);
        }

        $this->command->info('Role and Permission seeding completed successfully!');
    }
}
