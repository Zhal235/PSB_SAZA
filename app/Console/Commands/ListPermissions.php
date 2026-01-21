<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;

class ListPermissions extends Command
{
    protected $signature = 'permission:list';
    protected $description = 'List all available permissions';

    public function handle()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get();

        $this->info('Available Permissions:');
        $this->line('');

        $currentCategory = null;
        foreach ($permissions as $permission) {
            if ($currentCategory !== $permission->category) {
                $currentCategory = $permission->category;
                $this->line("📂 [{$permission->category}]");
            }
            $this->line("   • {$permission->name}");
            $this->line("     {$permission->description}");
        }

        $this->line('');
        $this->info("Total permissions: {$permissions->count()}");
    }
}