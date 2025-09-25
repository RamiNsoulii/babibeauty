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

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($customers as $customer) {
            $product = $products->random();
            Review::create([
                'user_id' => $customer->id,
                'reviewable_id' => $product->id,
                'reviewable_type' => Product::class,
                'rating' => rand(3, 5),
                'comment' => 'Sample review comment for ' . $product->name,
            ]);
        }
    }
}
