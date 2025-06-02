<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobAdvertisement;
use Illuminate\Support\Facades\DB;

class JobAdPortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portfolioExamples = [
            [
                'example_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1746724103/job-portfolio/172/sv7ecjlmjf6mpmyephxz.avif',
                'example_public_id' => 'job-portfolio/172/sv7ecjlmjf6mpmyephxz',
            ],
            [
                'example_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1746723963/job-portfolio/171/j7qydkfltstkmzluiibn.avif',
                'example_public_id' => 'job-portfolio/171/j7qydkfltstkmzluiibn',
            ],
            [
                'example_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1746722735/job-portfolio/170/cgj1e9w3nzxov7ayfruv.avif',
                'example_public_id' => 'job-portfolio/170/cgj1e9w3nzxov7ayfruv',
            ],
            [
                'example_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1748868080/job-portfolio/173/jcaeugu2b0xflil04gy7.jpg',
                'example_public_id' => 'job-portfolio/173/jcaeugu2b0xflil04gy7',
            ],
        ];

        $jobAds = JobAdvertisement::all();
        $i = 0;

        foreach ($jobAds as $jobAd) {
            $example = $portfolioExamples[$i % count($portfolioExamples)];

            DB::table('job_advertisements_portfolio')->insert([
                'job_ad_id' => $jobAd->id,
                'example_url' => $example['example_url'],
                'example_public_id' => $example['example_public_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $i++;
        }
    }
}
