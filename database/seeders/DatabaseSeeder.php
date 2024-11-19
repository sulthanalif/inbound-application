<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //Role
        $super_admin = Role::create(['name' => 'Super Admin']);
        $admin_engineer = Role::create(['name' => 'Admin Engineer']);
        $admin_werehouse = Role::create(['name' => 'Admin Werehouse']);
        $head_werehouse = Role::create(['name' => 'Head Werehouse']);


        $this->call(UserSeeder::class);


    }
}
