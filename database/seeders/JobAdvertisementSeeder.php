<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobAdvertisement;
use App\Models\User;
use Faker\Factory as Faker;

class JobAdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');
        $users = User::inRandomOrder()->take(10)->get();
        $statuses = ['pending', 'approved'];

        foreach ($users as $user) {
            $numAds = rand(1, 3);
            for ($i = 0; $i < $numAds; $i++) {
                JobAdvertisement::create([
                    'Title' => $faker->jobTitle,
                    'Description' => $faker->paragraph(4, true),
                    'creator' => $user->name,
                    'Examples' => $faker->sentence(6),
                    'Price' => $faker->randomFloat(2, 100, 3000),
                    'Status' => $faker->randomElement($statuses),
                ]);
            }
        }
    }
}
