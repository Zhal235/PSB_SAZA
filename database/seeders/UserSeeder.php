<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $calonSantriRole = \App\Models\Role::where('name', 'calon_santri')->first();

        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@psb-saza.local',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole?->id ?? 1,
        ]);

        // Sample Calon Santri
        User::create([
            'name' => 'Ahmad Ridho',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $calonSantriRole?->id ?? 4,
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $calonSantriRole?->id ?? 4,
        ]);
    }
}
