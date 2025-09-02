<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $products = Product::all();

        foreach ($products as $product) {
            $categories->random(2)->each(function ($category) use ($product) {
                $product->categories()->attach($category->id);
            });
        }
    }
}
