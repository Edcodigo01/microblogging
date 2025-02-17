<?php

namespace App\Jobs;

use App\Models\Tweets_following;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteAllTweetsFriendRemovedJob implements ShouldQueue
{
    use Queueable;
    protected $tweet_id;

    protected $tagCacheTweetsUsersFollowings = "users-tweets-followings";

    /**
     * Create a new job instance.
     */
    public function __construct($tweet_id)
    {
        $this->tweet_id = $tweet_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tweets_following::where('tweet_id', $this->tweet_id)->delete();
        Cache::tags([$this->tagCacheTweetsUsersFollowings])->flush();
    }
}
