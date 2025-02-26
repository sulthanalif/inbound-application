<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Unit;
use App\Models\User;
use App\Models\Goods;
use App\Models\Category;
use App\Models\DeliveryArea;
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


        $warehouse1 = Warehouse::create([
            'code' => 'WH01',
            'name' => 'Warehouse 1',
            'address' => 'Jl. Jenderal Sudirman No. 1',
        ]);

        $warehouse2 = Warehouse::create([
            'code' => 'WH02',
            'name' => 'Warehouse 2',
            'address' => 'Jl. Kenangan No. 2',
        ]);

        Area::create([
            'code' => 'A01',
            'name' => 'Area 1',
            'container' => 'Container 1',
            'rack' => 'Rack 1',
            'number' => 'Number 1',
            'warehouse_id' => $warehouse1->id,
        ]);

        Area::create([
            'code' => 'A02',
            'name' => 'Area 2',
            'container' => 'Container 2',
            'rack' => 'Rack 2',
            'number' => 'Number 2',
            'warehouse_id' => $warehouse1->id,
        ]);

        Area::create([
            'code' => 'B01',
            'name' => 'Area 1',
            'container' => 'Container 1',
            'rack' => 'Rack 1',
            'number' => 'Number 1',
            'warehouse_id' => $warehouse2->id,
        ]);

        Area::create([
            'code' => 'B02',
            'name' => 'Area 2',
            'container' => 'Container 2',
            'rack' => 'Rack 2',
            'number' => 'Number 2',
            'warehouse_id' => $warehouse2->id,
        ]);

        $category1 = Category::create([
            'name' => 'Category 1',
        ]);

        $category2 = Category::create([
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

        $pro = Project::create([
            'code' => 'PRJ01',
            'name' => 'Project 1',
            'start_date' => now(),
            'address' => 'Jl. Kenangan No. 1',
            'user_id' => User::where('name', 'Admin Engineer')->first()->id
        ]);

        $pro->statusProject()->create([
            'next' => false,
            'end' => true
        ]);

        for ($i=1 ; $i <= 10 ; $i++) {
            Goods::create([
                'name' => "Goods $i",
                'code' => "GD$i",
                'length' => rand(10, 100),
                'width' => rand(10, 100),
                'height' => rand(10, 100),
                'weight' => rand(1, 50),
                'description' => 'Description ' . $i,
                'condition' => 100,
                'price' => rand(1, 10) * 1000,
                'qty' => rand(1, 100),
                'type' => rand(0, 1) ? 'Rentable' : 'Consumable',
                'minimum_order' => rand(1, 10),
                'unit_time' => rand(1, 12) . ' months',
                'capital' => rand(500, 5000),
                'unit_id' => Unit::inRandomOrder()->first()->id,
                'vendor_id' => Vendor::inRandomOrder()->first()->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'area_id' => Area::inRandomOrder()->first()->id,
                'user_id' => User::whereHas('roles', function($query) {
                    $query->where('name', 'Admin Warehouse');
                })->inRandomOrder()->first()->id,
            ]);
        }

        DeliveryArea::create([
            'code' => 'DAR01',
            'name' => 'Delivery Area 1',
            'address' => 'Jl. Kenangan No. 1',
        ]);

    }
}

