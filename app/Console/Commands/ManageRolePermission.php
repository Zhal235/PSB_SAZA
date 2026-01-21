<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ManageRolePermission extends Command
{
    protected $signature = 'role:manage 
                            {action : add|remove|list|reset}
                            {role : Role name (admin, calon_santri, santri, petugas_pendaftaran, petugas_keuangan)}
                            {permission? : Permission name}';
    protected $description = 'Manage role permissions';

    public function handle()
    {
        $action = $this->argument('action');
        $role = $this->argument('role');
        $permission = $this->argument('permission');

        $validRoles = ['admin', 'calon_santri', 'santri', 'petugas_pendaftaran', 'petugas_keuangan'];
        
        if (!in_array($role, $validRoles)) {
            $this->error("Invalid role. Valid roles: " . implode(', ', $validRoles));
            return;
        }

        match($action) {
            'add' => $this->addPermission($role, $permission),
            'remove' => $this->removePermission($role, $permission),
            'list' => $this->listPermissions($role),
            'reset' => $this->resetPermissions($role),
            default => $this->error('Invalid action. Use: add, remove, list, or reset')
        };
    }

    private function addPermission($role, $permission)
    {
        $perm = Permission::where('name', $permission)->first();
        
        if (!$perm) {
            $this->error("Permission '{$permission}' not found");
            return;
        }

        $exists = DB::table('role_permissions')
            ->where('role', $role)
            ->where('permission_id', $perm->id)
            ->exists();

        if ($exists) {
            $this->warn("Permission '{$permission}' already assigned to role '{$role}'");
            return;
        }

        DB::table('role_permissions')->insert([
            'role' => $role,
            'permission_id' => $perm->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("✓ Permission '{$permission}' added to role '{$role}'");
    }

    private function removePermission($role, $permission)
    {
        $perm = Permission::where('name', $permission)->first();
        
        if (!$perm) {
            $this->error("Permission '{$permission}' not found");
            return;
        }

        $deleted = DB::table('role_permissions')
            ->where('role', $role)
            ->where('permission_id', $perm->id)
            ->delete();

        if ($deleted) {
            $this->info("✓ Permission '{$permission}' removed from role '{$role}'");
        } else {
            $this->warn("Permission '{$permission}' was not assigned to role '{$role}'");
        }
    }

    private function listPermissions($role)
    {
        $permissions = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role', $role)
            ->select('permissions.name', 'permissions.description', 'permissions.category')
            ->orderBy('permissions.category')
            ->orderBy('permissions.name')
            ->get();

        $this->info("Permissions for role: {$role}");
        
        if ($permissions->isEmpty()) {
            $this->warn('No permissions assigned');
            return;
        }

        $currentCategory = null;
        foreach ($permissions as $permission) {
            if ($currentCategory !== $permission->category) {
                $currentCategory = $permission->category;
                $this->line("[{$currentCategory}]");
            }
            $this->line("  ✓ {$permission->name}");
        }
    }

    private function resetPermissions($role)
    {
        if (!$this->confirm("Are you sure you want to reset permissions for role '{$role}'?")) {
            return;
        }

        DB::table('role_permissions')->where('role', $role)->delete();
        $this->info("✓ All permissions removed from role '{$role}'");
    }
}