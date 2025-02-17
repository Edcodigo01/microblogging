<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index(Request $request)
    {
        $users = $this->userRepository->index($request);

        return response()->json(["data" => $users]);
    }
    public function get($id)
    {
        $user = $this->userRepository->get($id);
        if (!$user)
            return response()->json(["message" => "No existe un usuario con el id: " . $id], 404);

        return response()->json(["data" => $user]);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $result = $this->userRepository->update($request, $id);
        if (@$result["error"])
            return response()->json(["message" => $result["error"]], $result["code"]);

        return response()->json(["message" => "Datos actualizados con éxito"]);
    }
}
