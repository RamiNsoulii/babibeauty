<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        foreach ($customers as $customer) {
            foreach ($products->random(3) as $product) {
                Review::create([
                    'user_id' => $customer->id,
                    'reviewable_id' => $product->id,
                    'reviewable_type' => Product::class,
                    'rating' => rand(1, 5),
                    'comment' => 'This is a review for ' . $product->name,
                ]);
            }
        }
    }
}
