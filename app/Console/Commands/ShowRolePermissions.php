<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowRolePermissions extends Command
{
    protected $signature = 'role:show-permissions {role? : Show permissions for a specific role}';
    protected $description = 'Show role and their permissions';

    public function handle()
    {
        $role = $this->argument('role');
        
        if ($role) {
            $this->showRolePermissions($role);
        } else {
            $this->showAllRolePermissions();
        }
    }

    private function showAllRolePermissions()
    {
        $roles = ['admin', 'calon_santri', 'santri', 'petugas_pendaftaran', 'petugas_keuangan'];
        
        foreach ($roles as $role) {
            $this->showRolePermissions($role);
            $this->line('');
        }
    }

    private function showRolePermissions($role)
    {
        $permissions = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role', $role)
            ->select('permissions.name', 'permissions.description', 'permissions.category')
            ->orderBy('permissions.category')
            ->orderBy('permissions.name')
            ->get();

        $this->info("📋 Role: {$role}");
        
        if ($permissions->isEmpty()) {
            $this->warn('   No permissions assigned');
            return;
        }

        $currentCategory = null;
        foreach ($permissions as $permission) {
            if ($currentCategory !== $permission->category) {
                $currentCategory = $permission->category;
                $this->line("   [{$currentCategory}]");
            }
            $this->line("     ✓ {$permission->name} - {$permission->description}");
        }
    }
}