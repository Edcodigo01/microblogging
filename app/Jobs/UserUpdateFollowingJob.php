<?php

namespace App\Jobs;

use App\Models\Tweets_following;
use App\Models\User;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UserUpdateFollowingJob implements ShouldQueue
{
    use Queueable;
    protected $user;
    protected $tagCacheTweetsUsersFollowings = "users-tweets-followings";

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Tweets_following::where('tweet_autor_id', $this->user->id)
            ->update(['tweet_autor_name' => $this->user->name]);

        Cache::tags([$this->tagCacheTweetsUsersFollowings])->flush();
    }
}
