<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\BeautyExpert;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $customer1 = User::where('email', 'customer1@example.com')->first();
        $expert1 = BeautyExpert::first();

        Booking::create([
            'customer_id' => $customer1->id,
            'expert_id' => $expert1->id,
            'booking_date' => now()->addDays(1),
            'status' => 'pending',
        ]);
    }
}
