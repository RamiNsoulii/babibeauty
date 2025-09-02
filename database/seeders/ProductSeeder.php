<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();

        foreach ($brands as $brand) {
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'brand_id' => $brand->id,
                    'name' => $brand->name . ' Product ' . $i,
                    'description' => 'Description for ' . $brand->name . ' Product ' . $i,
                    'price' => rand(10, 200),
                    'stock' => rand(5, 50),
                ]);
            }
        }
    }
}
