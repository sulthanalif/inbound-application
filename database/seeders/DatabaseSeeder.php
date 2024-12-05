<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Unit;
use App\Models\User;
use App\Models\Goods;
use App\Models\Category;
use App\Models\Project;
use App\Models\Vendor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Warehouse;
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
        $admin_warehouse = Role::create(['name' => 'Admin Warehouse']);
        $head_warehouse = Role::create(['name' => 'Head Warehouse']);


        $this->call(UserSeeder::class);


        Warehouse::create([
            'code' => 'WH01',
            'name' => 'Warehouse 1',
            'address' => 'Jl. Jenderal Sudirman No. 1',
        ]);

        Warehouse::create([
            'code' => 'WH02',
            'name' => 'Warehouse 2',
            'address' => 'Jl. Kenangan No. 2',
        ]);

        Category::create([
            'name' => 'Category 1',
        ]);

        Category::create([
            'name' => 'Category 2',
        ]);

        $units = [
            ['nama' => 'satuan', 'simbol' => 'pcs', 'deskripsi' => 'Per satuan'],
            ['nama' => 'lusin', 'simbol' => 'dz', 'deskripsi' => '12 satuan'],
            ['nama' => 'kodi', 'simbol' => 'kodi', 'deskripsi' => '144 satuan'],
            ['nama' => 'kg', 'simbol' => 'kg', 'deskripsi' => 'Kilogram'],
            ['nama' => 'gram', 'simbol' => 'g', 'deskripsi' => 'Gram'],
            ['nama' => 'ton', 'simbol' => 'ton', 'deskripsi' => 'Ton'],
            ['nama' => 'liter', 'simbol' => 'L', 'deskripsi' => 'Liter'],
            ['nama' => 'mililiter', 'simbol' => 'ml', 'deskripsi' => 'Mililiter'],
            ['nama' => 'deciliter', 'simbol' => 'dl', 'deskripsi' => 'Deciliter'],
            ['nama' => 'kiloliter', 'simbol' => 'kl', 'deskripsi' => 'Kiloliter'],
            ['nama' => 'meter', 'simbol' => 'm', 'deskripsi' => 'Meter'],
            ['nama' => 'centimeter', 'simbol' => 'cm', 'deskripsi' => 'Centimeter'],
            ['nama' => 'kilometer', 'simbol' => 'km', 'deskripsi' => 'Kilometer'],
            ['nama' => 'inci', 'simbol' => 'in', 'deskripsi' => 'Inci'],
            ['nama' => 'rim', 'simbol' => 'rim', 'deskripsi' => '500 lembar'],
            ['nama' => 'roll', 'simbol' => 'roll', 'deskripsi' => 'Gulungan'],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit['nama'],
                'symbol' => $unit['simbol'],
                'description' => $unit['deskripsi'],
            ]);
        }

        Vendor::create([
            'name' => 'Vendor 1',
            'email' => 'Zs8d4@example.com',
            'address' => 'Jl. Jenderal Sudirman No. 1',
            'phone' => '08123456789',
        ]);

        Project::create([
            'code' => 'PRJ01',
            'name' => 'Project 1',
            'start_date' => now(),
            'address' => 'Jl. Kenangan No. 1',
            'user_id' => User::where('name', 'Admin Engineer')->first()->id
        ]);

        Area::create([
            'code' => 'AR01',
            'name' => 'Area 1',
            'address' => 'Jl. Kenangan No. 1',
        ]);

        Area::create([
            'code' => 'AR02',
            'name' => 'Area 2',
            'address' => 'Jl. Kenangan No. 2',
        ]);
    }
}

