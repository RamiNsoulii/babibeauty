<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::create(['name' => 'Luxe Beauty', 'description' => 'Luxury makeup brand.']);
        Brand::create(['name' => 'Glow Cosmetics', 'description' => 'Everyday skincare brand.']);
    }
}
