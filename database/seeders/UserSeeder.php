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
        $super_admin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
        $super_admin->assignRole('Super Admin');

        $admin_engineer = User::create([
            'name' => 'Admin Engineer',
            'email' => 'adminengineer@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
        $admin_engineer->assignRole('Admin Engineer');

        $admin_werehouse = User::create([
            'name' => 'Admin Werehouse',
            'email' => 'adminwerehouse@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
        $admin_werehouse->assignRole('Admin Werehouse');

        $head_werehouse = User::create([
            'name' => 'Head Werehouse',
            'email' => 'headwerehouse@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
        $head_werehouse->assignRole('Head Werehouse');

    }
}
