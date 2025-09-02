<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer1 = User::where('email', 'customer1@example.com')->first();

        Order::create([
            'user_id' => $customer1->id,
            'status' => 'pending',
            'total_price' => 40.00,
        ]);
    }
}
