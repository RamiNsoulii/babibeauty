<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $luxe = Brand::where('name', 'Luxe Beauty')->first();
        $glow = Brand::where('name', 'Glow Cosmetics')->first();

        Product::create([
            'brand_id' => $luxe->id,
            'name' => 'Luxe Lipstick',
            'description' => 'Long-lasting luxury lipstick.',
            'price' => 25.00,
            'stock' => 100,
        ]);

        Product::create([
            'brand_id' => $glow->id,
            'name' => 'Glow Face Cream',
            'description' => 'Hydrating daily face cream.',
            'price' => 15.00,
            'stock' => 200,
        ]);
    }
}
