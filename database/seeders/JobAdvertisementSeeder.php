<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobAdvertisement;
use App\Models\User;
use Faker\Factory as Faker;

class JobAdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
        {
                $faker = Faker::create('en_US');
                $users = User::inRandomOrder()->take(10)->get();
                $statuses = ['active', 'closed', 'draft'];


                foreach ($users as $user) {
                    $numAds = rand(1, 3);
                    for ($i = 0; $i < $numAds; $i++) {
                    JobAdvertisement::factory()->create([
                        'Title' => $faker->jobTitle,
                        'Description' => $faker->paragraph(4, true),
                        'creator' => $user->name,
                        'creator_id' => $user->id,
                        'Examples' => $faker->sentence(6),
                        'Price' => $faker->randomFloat(2, 100, 3000),
                        'Status' => $faker->randomElement($statuses),
                    ]);
                }
            }
        }
}
