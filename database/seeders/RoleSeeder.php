<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'farmer', 'officer'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@psareco.com',
            'password' => Hash::make('admin123'),
            'status' => 'active',
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $officer = User::create([
            'name' => 'Maria Santos',
            'email' => 'officer@psareco.com',
            'password' => Hash::make('officer123'),
            'must_change_password' => false,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $officer->assignRole('officer');

        $farmer = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'farmer@psareco.com',
            'password' => Hash::make('farmer123'),
            'must_change_password' => false,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $farmer->assignRole('farmer');
    }
}

