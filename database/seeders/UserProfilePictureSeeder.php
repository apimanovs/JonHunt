<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserProfilePictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAvatarPictures = [
            [
                'photo_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1748868232/avatars/mymtrqedfhzxezswf1mj.webp',
                'cloudinary_public_id' => 'avatars/mymtrqedfhzxezswf1mj',
            ],
            [
                'photo_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1748868220/avatars/byrqnxdibevmt9jyaots.jpg',
                'cloudinary_public_id' => 'avatars/byrqnxdibevmt9jyaots',
            ],
            [
                'photo_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1748868177/avatars/gbfp2asa6axvknai3zkl.jpg',
                'cloudinary_public_id' => 'avatars/gbfp2asa6axvknai3zkl',
            ],
            [
                'photo_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1748868161/avatars/fzsae01ff0e3ue8ve3ip.webp',
                'cloudinary_public_id' => 'avatars/fzsae01ff0e3ue8ve3ip',
            ],            
            [
                'photo_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1746959641/avatars/w2rrzzgi1jm7i1sbytl7.avif',
                'cloudinary_public_id' => 'avatars/w2rrzzgi1jm7i1sbytl7',
            ],
            [
                'photo_url' => 'https://res.cloudinary.com/dy55d3tu4/image/upload/v1746862205/avatars/nspcl1dp3xjlso0nxjuh.avif',
                'cloudinary_public_id' => 'avatars/nspcl1dp3xjlso0nxjuh',
            ],
        ];

        $users = User::all();
        $i = 0;

        foreach ($users as $user) {
            $avatar = $userAvatarPictures[$i % count($userAvatarPictures)];

            DB::table('avatars')->insert([
                'user_id' => $user->id,
                'photo_url' => $avatar['photo_url'],
                'cloudinary_public_id' => $avatar['cloudinary_public_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $i++;
        }
    }
}
