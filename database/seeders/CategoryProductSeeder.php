<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $products = Product::all();

        // Assign each product to 1-2 categories
        foreach ($products as $product) {
            $assignedCategories = $categories->random(rand(1,2))->pluck('id')->toArray();
            foreach ($assignedCategories as $categoryId) {
                DB::table('category_product')->insert([
                    'category_id' => $categoryId,
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
