<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@coreaxis.cloud'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('SuperAdmin@123'),
                'role'      => 'super_admin',
                'phone'     => '+91 9113107586',
                'is_active' => true,
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@coreaxis.cloud'],
            [
                'name'      => 'Admin User',
                'password'  => Hash::make('Admin@123'),
                'role'      => 'admin',
                'phone'     => '+91 9113107586',
                'is_active' => true,
            ]
        );

        // Manager
        User::updateOrCreate(
            ['email' => 'manager@coreaxis.cloud'],
            [
                'name'      => 'Branch Manager',
                'password'  => Hash::make('Manager@123'),
                'role'      => 'manager',
                'phone'     => '+91 9113107586',
                'is_active' => true,
            ]
        );

        echo "✓ Super Admin  → superadmin@coreaxis.cloud  / SuperAdmin@123\n";
        echo "✓ Admin        → admin@coreaxis.cloud       / Admin@123\n";
        echo "✓ Manager      → manager@coreaxis.cloud     / Manager@123\n";
    }
}
