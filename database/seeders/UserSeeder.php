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
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@psb-saza.local',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Sample Calon Santri
        User::create([
            'name' => 'Ahmad Ridho',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password123'),
            'role' => 'calon_santri'
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'role' => 'calon_santri'
        ]);
    }
}
