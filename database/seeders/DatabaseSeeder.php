<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BeautyExpertSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            ReviewSeeder::class,
            BookingSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            CategoryProductSeeder::class,
        ]);
    }
}
