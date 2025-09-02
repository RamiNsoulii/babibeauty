<?php

namespace Database\Seeders;

use App\Models\BeautyExpert;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeautyExpertSeeder extends Seeder
{
    public function run(): void
    {
        $experts = User::where('role', 'expert')->get();

        foreach ($experts as $expert) {
            BeautyExpert::create([
                'user_id' => $expert->id,
                'bio' => 'Experienced beauty expert specializing in skincare.',
                'specialization' => 'Skincare',
                'experience_years' => rand(1, 10),
                'hourly_rate' => rand(20, 100),
            ]);
        }
    }
}
