<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'code' => 'PRD001',
            'name' => 'Product 1',
            'description' => 'Description for product 1',
            'items_per_pack' => 10,
            'photo' => '/uploads/profile/admin-avatar.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'code' => 'PRD002',
            'name' => 'Product 2',
            'description' => 'Description for product 2',
            'items_per_pack' => 20,
            'photo' => '/uploads/profile/admin-avatar.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
