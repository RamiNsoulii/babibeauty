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
        $customers = User::where('role', 'customer')->get();
        $experts = BeautyExpert::all();

        foreach ($customers as $customer) {
            $expert = $experts->random();
            Booking::create([
                'customer_id' => $customer->id,
                'expert_id' => $expert->id,
                'booking_date' => now()->addDays(rand(1, 30)),
                'status' => 'pending',
            ]);
        }
    }
}
