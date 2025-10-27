<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        Admin::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gnraw.com',
            'password' => Hash::make('Momen@123'),
            'is_active' => true,
        ]);

        // Create additional admin users if needed
        Admin::create([
            'name' => 'Momentarek',
            'username' => 'momentarek',
            'email' => 'Momentarek@gnraw.com',
            'password' => Hash::make('123456'),
            'is_active' => true,
        ]);
    }
}
