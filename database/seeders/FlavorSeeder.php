<?php

namespace Database\Seeders;

use App\Models\Flavor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlavorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Flavor::create([
            'product_id' => 1,
            'flavor_name' => 'Vanilla',
            'price_retail' => 10.00,
            'price_pack' => 90.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Flavor::create([
            'product_id' => 2,
            'flavor_name' => 'Chocolate',
            'price_retail' => 12.00,
            'price_pack' => 110.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
