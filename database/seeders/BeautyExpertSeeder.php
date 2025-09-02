<?php

namespace Database\Seeders;

use App\Models\BeautyExpert;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeautyExpertSeeder extends Seeder
{
    public function run(): void
    {
        $expert1 = User::where('email', 'expert1@example.com')->first();
        $expert2 = User::where('email', 'expert2@example.com')->first();

        BeautyExpert::create([
            'user_id' => $expert1->id,
            'bio' => 'Experienced makeup artist.',
            'specialization' => 'Makeup',
            'experience_years' => 5,
            'hourly_rate' => 50.00,
        ]);

        BeautyExpert::create([
            'user_id' => $expert2->id,
            'bio' => 'Professional hairstylist.',
            'specialization' => 'Hair',
            'experience_years' => 7,
            'hourly_rate' => 60.00,
        ]);
    }
}
