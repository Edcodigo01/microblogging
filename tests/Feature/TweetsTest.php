<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TweetsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_tweets(): void
    {
        $response = $this->get('/api/users-tweets/1');

        $response->assertStatus(200);
    }
}
