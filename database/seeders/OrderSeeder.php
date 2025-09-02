<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        foreach ($customers as $customer) {
            $order = Order::create([
                'user_id' => $customer->id,
                'status' => 'pending',
                'total_price' => 0, // will update after adding items
            ]);

            $total = 0;
            foreach ($products->random(3) as $product) {
                $quantity = rand(1, 5);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
                $total += $product->price * $quantity;
            }

            $order->update(['total_price' => $total]);
        }
    }
}
