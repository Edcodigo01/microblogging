<?php

namespace Tests\Feature;

use App\Http\Repositories\AuthRepository;
use App\Models\User;
use Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class addTweetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_add_tweet(): void
    {
        Cache::tags(["users-tweets-followings", "users-tweets"])->flush();

        $user = User::find(2);
        
        if (!$user)
            $user = User::factory()->create();

        $access_token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        $data = [
            'content' => "fake text",
        ];

        $response = $this->post('/api/users-tweets', $data, [
            'Authorization' => 'Bearer ' . $access_token,
            "Accept" => "application/json"
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas(
            'tweets',
            $data
        );

       
    }
}
