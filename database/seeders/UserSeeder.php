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
            'company' => 'PT. ABC',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
        $admin_engineer->assignRole('Admin Engineer');

        for ($i = 1; $i <= 5; $i++) {
            $admin_warehouse = User::create([
                'name' => "Admin Warehouse $i",
                'email' => "adminwarehouse$i@gmail.com",
                'password' => Hash::make('password'),
                'is_active' => true
            ]);
            $admin_warehouse->assignRole('Admin Warehouse');
        }

        $head_warehouse = User::create([
            'name' => 'Head Warehouse',
            'email' => 'headwarehouse@gmail.com',
            'password' => Hash::make('password'),
            'is_active' => true
        ]);
        $head_warehouse->assignRole('Head Warehouse');

    }
}
