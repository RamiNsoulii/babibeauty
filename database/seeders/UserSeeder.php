<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Experts
        User::create([
            'name' => 'Expert One',
            'email' => 'expert1@example.com',
            'role' => 'expert',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Expert Two',
            'email' => 'expert2@example.com',
            'role' => 'expert',
            'password' => Hash::make('password123'),
        ]);

        // Customers
        User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'role' => 'customer',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'role' => 'customer',
            'password' => Hash::make('password123'),
        ]);
    }
}
