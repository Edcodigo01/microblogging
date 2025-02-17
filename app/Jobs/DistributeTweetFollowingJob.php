<?php

namespace App\Jobs;

use App\Models\Tweet;
use Cache;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class DistributeTweetFollowingJob implements ShouldQueue
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
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
        \Log::error('This is an error message from the controller.');
        // obtener el nombre del autor del tweeter
        $tweetAuthor = DB::table('users')->where('id', $this->tweet->user_id)->first();
        // obtener los seguidores del autor del tweet
        $followers = DB::table('followings')
            ->where('following_id', $this->tweet->user_id)
            ->pluck('user_id');
        // se preparan los datos en formato array
        $data = $followers->map(function ($userId) use ($tweetAuthor) {
            return [
                'user_id' => $userId, // a quienes van dirigido
                'tweet_id' => $this->tweet->id,
                'tweet_autor_id' => $this->tweet->user_id,
                'tweet_autor_name' => $tweetAuthor->name,
                'content' => $this->tweet->content,
                'created_at' => $this->tweet->created_at,
                'updated_at' => $this->tweet->created_at
            ];
        })->toArray();
        // se insertan de forma masiva (mas optimizado)
        DB::table('tweets_followings')->insert($data);
       
        Cache::tags([$this->tagCacheTweetsUsersFollowings])->flush();
    }
}
