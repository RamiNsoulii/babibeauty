<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all products
        $products = Product::all();

        foreach ($products as $product) {
            // Create 2-3 images per product with placeholders
            DB::table('product_images')->insert([
                [
                    'product_id' => $product->id,
                    'image_path' => 'images/product_'.$product->id.'_1.jpg',
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'product_id' => $product->id,
                    'image_path' => 'images/product_'.$product->id.'_2.jpg',
                    'is_primary' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
