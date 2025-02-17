<?php

namespace App\Http\Repositories;

use App\Models\Following;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;

class FollowingRepository
{
    protected $tagCacheFollowings = "followings-of-users-page";
    protected $tagCacheTweetsUsersFollowings = "users-tweets-followings";

    public function index(Request $request, int $id)
    {
        $following = new Following();
        $remember = $this->tagCacheFollowings . '-' . $request->page . '-userid-' . $id;

        return Cache::tags([$this->tagCacheFollowings])->remember($remember, now()->addMinutes(10), function () use ($following, $id) {
            return $following->where("user_id", $id)->orderBy("created_at", "desc")->with(['following:id,name,email,created_at'])->paginate(20)->pluck("following");
        });
    }

    public function store(int $following_id)
    {
        $auth_id = Auth()->user()->id;

        if ($following_id == $auth_id)
            return ["error" => "No puedes seguir a tu mismo usuario.", "code" => 403];

        $following = User::find($following_id);

        if (!$following)
            return ["error" => "El usuario al que intentas seguir no existe o fue deshabilitado.", "code" => 404];

        $existInList = Following::where("user_id", $auth_id)->where("following_id", $following_id)->exists();

        if ($existInList)
            return ["error" => "Ya sigues a este usuario.", "code" => 403];

        // Se limpian los registros de cache (redis) para los endpoints de listar tweets de usuarios y tweets de amigos.
        Cache::tags([$this->tagCacheFollowings, $this->tagCacheTweetsUsersFollowings])->flush();

        $following = new Following();
        return $following->fill(["user_id" => $auth_id, "following_id" => $following_id])->save();
    }

    public function destroy(int $following_id)
    {
        $user_id = Auth()->user()->id;

        if ($following_id == $user_id)
            return ["error" => "No puedes dejar de seguir a tu misma cuenta.", "code" => 403];

        $following = Following::where("user_id", $user_id)->where("following_id", $following_id)->first();

        if (!$following)
            return ["error" => "Ya no sigues a este usuario, o no existe.", "code" => 403];

        $following->delete();

        // Se limpian los registros de cache (redis) para los endpoints de listar tweets de usuarios y tweets de amigos.
        Cache::tags([$this->tagCacheFollowings, $this->tagCacheTweetsUsersFollowings])->flush();
    }
}