<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Luxe Beauty', 'Glow Up', 'Skin Master', 'Hair Pro', 'Makeup Magic'];

        foreach ($brands as $name) {
            Brand::create(['name' => $name, 'description' => $name . ' products.']);
        }
    }
}
