<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $order = Order::first();
        $lipstick = Product::where('name', 'Luxe Lipstick')->first();
        $cream = Product::where('name', 'Glow Face Cream')->first();

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $lipstick->id,
            'quantity' => 1,
            'price' => $lipstick->price,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cream->id,
            'quantity' => 1,
            'price' => $cream->price,
        ]);
    }
}
