<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Administrator role with full access',
            ]
        );

        Role::firstOrCreate(
            ['name' => 'calon_santri'],
            [
                'display_name' => 'Calon Santri',
                'description' => 'Student applicant role',
            ]
        );

        Role::firstOrCreate(
            ['name' => 'santri'],
            [
                'display_name' => 'Santri',
                'description' => 'Active student role',
            ]
        );

        Role::firstOrCreate(
            ['name' => 'petugas_pendaftaran'],
            [
                'display_name' => 'Petugas Pendaftaran',
                'description' => 'Registration officer role',
            ]
        );

        Role::firstOrCreate(
            ['name' => 'petugas_keuangan'],
            [
                'display_name' => 'Petugas Keuangan',
                'description' => 'Finance officer role',
            ]
        );
    }
}
