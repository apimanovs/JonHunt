<?php

namespace Database\Seeders;

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
            'name' => 'Daniel Foster',
            'email' => 'daniel.foster.personal@gmail.com',
            'username' => 'danielf',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
