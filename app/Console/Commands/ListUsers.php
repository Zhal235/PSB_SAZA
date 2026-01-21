<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    protected $signature = 'user:list {--search= : Search by name}';
    protected $description = 'List all users';

    public function handle()
    {
        $search = $this->option('search');
        
        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")->get();
            $this->info("Searching for users with name containing: {$search}");
        } else {
            $users = User::all();
            $this->info("All users:");
        }

        if ($users->isEmpty()) {
            $this->warn('No users found.');
            return;
        }

        $this->table(['ID', 'Name', 'Email', 'Role'], 
            $users->map(function ($user) {
                return [$user->id, $user->name, $user->email, $user->role];
            })
        );
    }
}