<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {

        $categories = [
            ['name' => 'Makeup', 'description' => 'All kinds of makeup products.'],
            ['name' => 'Skincare', 'description' => 'Skincare and facial care products.'],
            ['name' => 'Haircare', 'description' => 'Hair products for all types.'],
            ['name' => 'Fragrance', 'description' => 'Perfumes and scents for men and women.'],
            ['name' => 'Nail', 'description' => 'Nail polishes and manicure/pedicure products.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']], // unique key
                ['description' => $category['description']]
            );
        }
    }
}
