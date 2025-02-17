<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FollowingRepository;
use Illuminate\Http\Request;

class FollowingController extends Controller
{
    private $followingRepository;
    public function __construct(FollowingRepository $followingRepository)
    {
        $this->followingRepository = $followingRepository;
    }
    public function index(Request $request, $id)
    {
        $followings = $this->followingRepository->index($request, $id);

        return response()->json(["data" => $followings]);
    }

    public function store($following_id)
    {
        $result = $this->followingRepository->store($following_id);

        if (@$result["error"])
            return response()->json(["message" => $result["error"]], $result["code"]);

        return response()->json(["message" => "Usuario agregado con éxito a tu lista de amigos."]);
    }

    public function destroy($id)
    {
        $result = $this->followingRepository->destroy($id);

        if (@$result["error"])
            return response()->json(["message" => $result["error"]], $result["code"]);

        return response()->json(["message" => "Usuario eliminado de tu lista de amigos."]);
    }
}
