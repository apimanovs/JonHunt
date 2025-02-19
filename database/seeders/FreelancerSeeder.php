<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Freelancer;

class FreelancerSeeder extends Seeder
{
    public function run()
    {
        $users = User::inRandomOrder()->take(5)->get(); 

        foreach ($users as $user) {
            Freelancer::create([
                'user_id'         => $user->id,
                'country'         => fake()->country(),
                'bio'             => fake()->paragraph(),
                'experience_from' => fake()->numberBetween(1, 3), 
                'experience_to'   => fake()->numberBetween(4, 10),           
            ]);

            $user->update(['role' => 'freelancer']);
        }
    }
}
