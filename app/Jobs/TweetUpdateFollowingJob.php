<?php

namespace App\Jobs;

use App\Models\Tweet;
use App\Models\Tweets_following;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TweetUpdateFollowingJob implements ShouldQueue
{
    use Queueable;
    protected $tweet;
    protected $tagCacheTweetsUsersFollowings = "users-tweets-followings";

    /**
     * Create a new job instance.
     */
    public function __construct(Tweet $tweet)
    {
        $this->tweet = $tweet;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tweets_following::where('tweet_id', $this->tweet->id)
            ->update(['content' => $this->tweet->content, "updated_at" => $this->tweet->updated_at]);

        Cache::tags([$this->tagCacheTweetsUsersFollowings])->flush();

    }
}
