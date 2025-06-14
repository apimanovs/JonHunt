<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            FreelancerSeeder::class,
            ProjectSeeder::class,
            JobAdvertisementSeeder::class,
            SkillSeeder::class,
            JobAdPortfolioSeeder::class,
            UserProfilePictureSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
