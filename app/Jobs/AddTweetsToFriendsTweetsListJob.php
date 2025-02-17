<?php

namespace App\Jobs;

use App\Models\Following;
use App\Models\Tweet;
use App\Models\User;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AddTweetsToFriendsTweetsListJob implements ShouldQueue
{
    use Queueable;
    protected $dataFollowing;

    /**
     * Create a new job instance.
     */
    public function __construct(Following $dataFollowing)
    {
        $this->dataFollowing = $dataFollowing;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // cuando se realiza la acción para seguir un usuario, se ejecuta esto para añadir los tweets del nuevo amigo a la lista de quien agrega
        $dataFollowing = $this->dataFollowing;
        $tweetsAuthor = Tweet::where("user_id", $dataFollowing->following_id)->get();
        $autor = User::find($dataFollowing->following_id);

        $data = $tweetsAuthor->map(function ($tweet) use ($dataFollowing, $autor) {
            return [
                "user_id" => $dataFollowing->user_id,
                "tweet_id" => $tweet->id,
                "tweet_autor_id" => $tweet->user_id,
                "tweet_autor_name" => $autor->name,
                "content" => $tweet->content,
                'created_at' => $tweet->created_at,
                'updated_at' => $tweet->updated_at
            ];
        })->toArray();

        // se insertan de forma masiva (mas optimizado)
        DB::table('tweets_followings')->insert($data);
    }
}
