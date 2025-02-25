<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_US');
        $users = User::inRandomOrder()->take(20)->get();

        $niches = [
            'Web Development',
            'Mobile App Development',
            'Digital Marketing',
            'Graphic Design',
            'Content Creation',
            'SEO',
            'E-commerce',
            'Software Engineering'
        ];

        $statuses = ['approved'];

        foreach ($users as $user) {
            $numProjects = rand(1, 3);
            for ($i = 0; $i < $numProjects; $i++) {
                Project::create([
                    'title' => $faker->company . ': ' . ucfirst($faker->bs),
                    'creator_id' => $user->id,
                    'creator' => $user->name,
                    'description' => $faker->paragraph(4, true), 
                    'niche' => $faker->randomElement($niches),
                    'completion_date' => $faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                    'budget' => $faker->randomFloat(2, 500, 5000),
                    'status' => $faker->randomElement($statuses),
                ]);
            }
        }
    }
}
