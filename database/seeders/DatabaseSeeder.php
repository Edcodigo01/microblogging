<?php

namespace Database\Seeders;

use App\Models\Following;
use App\Models\Tweet;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Cache;
use Database\Factories\FollowingFactory;
use Hash;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usersTotal = 1000;
        User::factory($usersTotal)->create();
        $this->addFollowing(1, 20, 10, $usersTotal);
        $this->addFollowing(21, $usersTotal, 3, $usersTotal);
        $this->addUserTweets($usersTotal);
        Cache::tags(["users-list-page", "followings-of-users-page", "users-tweets-followings", "users-tweets", "users", "user"])->flush();
    }

    public function addFollowing($startId, $endId, $cantReg, $maxId)
    {
        $array = [];
        for ($id = $startId; $id <= $endId; $id++) {
            if ($id > $maxId) {
                break;
            }

            for ($relation_id = $id; $relation_id <= $cantReg + $id; $relation_id++) {
                if ($relation_id > $maxId) {
                    break;
                }
                if ($id != $relation_id) {
                    Following::create([
                        'user_id' => $id,
                        'following_id' => $relation_id,
                    ]);
                }
            }
        }

        return $array;
    }

    public function addUserTweets($usersTotal)
    {
        $faker = Faker::create();
        for ($id = 1; $id <= $usersTotal; $id++) {
            $tweets = rand(1, 5);

            for ($f = 0; $f <= $tweets; $f++) {
                $minutesDiff = rand(1, 10);

                Tweet::create([
                    'user_id' => $id,
                    'content' => $faker->text(50),
                    'created_at' => now()->subMinutes($minutesDiff)
                ]);
            }
        }
    }

}
