<?php

namespace App\Http\Repositories;

use App\Models\User;
use Auth;
use Cache;
use Illuminate\Http\Request;

class UserRepository
{

    public function index(Request $request)
    {
        $tagCacheUsers = "users-list-page";
        // verifica si existe en db cache, los registros de usuarios por página, si existen los extrae, si no realiza la consulta a PostgreSQL
        return Cache::tags([$tagCacheUsers])->remember($tagCacheUsers . '-page-' . $request->page, now()->addMinutes(60), function () {
            return User::select("id", "name", "email", "created_at")->orderBy("created_at", "desc")->paginate(20);
        });
    }

    public function get(int $id)
    {
        // verifica si existe en db cache, el registro, si existe los extrae, si no realiza la consulta a PostgreSQL
        return Cache::tags(["user" . $id, "user"])->remember("user" . $id, now()->addMinutes(60), function () use ($id) {
            return User::select("id", "name", "email", "created_at")->find($id);
        });
    }

    public function update(Request $request, int $id)
    {
        $user_id = auth()->user()->id;

        if ($id != $user_id)
            return ["error" => "No tienes permisos para editar datos ajenos a tu cuenta.", "code" => 403];

        $user = User::find($id);
        $user->name = $request->name;
        $user->save();

        // Limpia de la base de datos en memoria, los registros relacionados a listar usuarios y userid
        Cache::tags(['users-list-page', 'user' . $id])->flush();
    }
}