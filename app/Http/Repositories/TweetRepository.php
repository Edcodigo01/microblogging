<?php

namespace App\Http\Repositories;
use App\Models\Tweet;
use App\Models\Tweets_following;
use App\Models\User;
use Auth;
use Cache;
use Illuminate\Http\Request;

class TweetRepository
{
    protected $tagCacheTweetsOfUser = "users-tweets";
    protected $tagCacheTweetsUsersFollowings = "users-tweets-followings";
    public function store(Request $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);
        $tweet = new Tweet();
        $tweet->create($request->all());

        Cache::tags([$this->tagCacheTweetsOfUser])->flush();
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $tweet = Tweet::find($id);

        if (!$tweet)
            return ["error" => "El tweet no existe", "code" => 404];
        else if ($tweet->user_id != $user_id)
            return ["error" => "No tienes permisos para editar datos ajenos a tu cuenta.", "code" => 403];

        $tweet->update(["content" => $request->content]);

        Cache::tags([$this->tagCacheTweetsOfUser])->flush();
    }


    public function tweetsOfUser(Request $request, int $id)
    {
        $rememberCache = $this->tagCacheTweetsOfUser . '-page-' . $request->page . '-user_id-' . $id;
        return Cache::tags([$this->tagCacheTweetsOfUser])->remember($rememberCache, now()->addMinutes(60), function () use ($id) {
            return Tweet::select("id", "content", "created_at")->where("user_id", $id)->orderBy("created_at", "desc")->paginate(20);
        });
    }

    public function tweetsOfFollowings($request, int $user_id)
    {
        $rememberCache = $this->tagCacheTweetsUsersFollowings . '-page-' . $request->page . '-user_id-' . $user_id;
        
        return Cache::tags([$this->tagCacheTweetsUsersFollowings])->remember($rememberCache, now()->addMinutes(60), function () use ($user_id) {
            return Tweets_following::where("user_id", $user_id)->select('tweet_id', 'content', 'tweet_autor_id', 'tweet_autor_name', 'created_at')
                ->latest()
                ->paginate(20);
        });
    }

    public function destroy($id)
    {
        $tweet = Tweet::find($id);
        $user_id = Auth::user()->id;

        if (!$tweet)
            return ["error" => "El tweet no existe", "code" => 404];
        else if ($tweet->user_id != $user_id)
            return ["error" => "No tienes permisos para editar datos ajenos a tu cuenta.", "code" => 403];

        $tweet->delete();

        Cache::tags([$this->tagCacheTweetsOfUser, $this->tagCacheTweetsUsersFollowings])->flush();
    }
}