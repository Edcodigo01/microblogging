<?php

namespace App\Http\Repositories;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthRepository
{

    public function register(Request $request)
    {
        $request->merge(['password' => Hash::make($request->password)]);
        User::create($request->all());
        // Limpia de la base de datos en memoria, los registros relacionados a listar usuarios
        Cache::tags('users-list-page')->flush();
    }
    
    public function login(Request $request)
    {
        $user = User::select("id", "name", "email", "password")->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ["error" => "Las credenciales son inválidas.", "code" => 401];
        }

        $access_token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return compact("user", "access_token");
    }
}