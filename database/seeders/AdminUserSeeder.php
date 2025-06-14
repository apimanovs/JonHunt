<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_US');

        User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'username' => $faker->unique()->userName,
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}