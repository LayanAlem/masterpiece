<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a predefined super admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@jordantourism.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
        ]);

        // Create a predefined regular admin
        Admin::create([
            'name' => 'System Admin',
            'email' => 'admin@jordantourism.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create 3 additional random admins
        Admin::factory(3)->create();
    }
}
