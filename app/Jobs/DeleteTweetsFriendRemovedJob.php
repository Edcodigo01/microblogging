<?php

namespace App\Jobs;

use App\Models\Tweets_following;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteTweetsFriendRemovedJob implements ShouldQueue
{
    use Queueable;
    protected $user_id;
    protected $following_id;

    protected $tagCacheTweetsUsersFollowings = "users-tweets-followings";

    /**
     * Create a new job instance.
     */
    public function __construct($user_id, $following_id)
    {
        $this->user_id = $user_id;
        $this->following_id = $following_id;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tweets_following::where('tweet_autor_id', $this->following_id)->where('user_id', $this->user_id)->delete();
        Cache::tags([$this->tagCacheTweetsUsersFollowings])->flush();
    }
}
