<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TweetRepository;

use App\Http\Requests\Tweet\TweetRequest;
use App\Http\Requests\Tweet\TweetUpdateRequest;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    private $tweetRepository;
    public function __construct(TweetRepository $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }

    public function store(TweetRequest $request)
    {
        $this->tweetRepository->store($request);

        return response()->json(["message" => "Tweet creado con éxito"]);
    }

    public function update(TweetUpdateRequest $request, $id)
    {
        $result = $this->tweetRepository->update($request, $id);

        if (@$result["error"])
            return response()->json(["message" => $result["error"]], $result["code"]);

        return response()->json(["message" => "Tweet actualizado con éxito"]);
    }

    public function tweetsOfUser(Request $request, $id)
    {
        $tweets = $this->tweetRepository->tweetsOfUser($request, (int) $id);
        return response()->json(["data" => $tweets]);
    }

    public function tweetsOfFollowings(Request $request)
    {
        $user_id = auth()->user()->id;

        $tweets = $this->tweetRepository->tweetsOfFollowings($request, $user_id);

        return response()->json(["data" => $tweets]);
    }

    public function destroy($id)
    {
        $result = $this->tweetRepository->destroy($id);

        if (@$result["error"])
            return response()->json(["message" => $result["error"]], $result["code"]);

        return response()->json(["message" => "Tweet eliminado con éxito"]);
    }
}
