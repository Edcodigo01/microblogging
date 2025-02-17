<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AuthRepository;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    private $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    protected function register(RegisterRequest $request)
    {
        $this->authRepository->register($request);
        
        return response()->json([
            'message' => 'Usuario registrado con éxito.',
        ]);
    }

    protected function login(LoginRequest $request)
    {
        $data = $this->authRepository->login($request);

        if (@$data["error"])
            return response()->json(["message" => $data["error"]], $data["code"]);

        return response()->json(["data" => $data]);
    }

    protected function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "Sesión cerrada con éxito"
        ]);
    }
}
