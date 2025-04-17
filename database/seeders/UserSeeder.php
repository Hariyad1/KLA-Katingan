<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
         // Create Admin
        User::create([
             'name' => 'Administrator',
             'email' => 'admin@gmail.com',
             'password' => Hash::make('admin'),
             'status' => 1,
         ]);

        // Create Regular User
        User::create([
            'name' => 'User Demo',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user'),
            'status' => 0,
        ]);

        // Create some demo users
        User::factory(3)->create(['status' => 0]);
    }
} 