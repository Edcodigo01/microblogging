<?php

use App\Http\Controllers\followingController;
use App\Http\Controllers\TweetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// autenticación
Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    Route::post('auth/logout', 'logout')->middleware('auth:sanctum');
});

// usuarios
Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::get('users/{id}', 'get');
    Route::put('users/{id}', 'update')->middleware('auth:sanctum');
});

// acciones seguir
Route::controller(followingController::class)->group(function () {
    Route::get('users-following/{id}', 'index');
    Route::post('users-following/{following_id}', 'store')->middleware('auth:sanctum');
    Route::delete('users-following/{following_id}', 'destroy')->middleware('auth:sanctum');
});

// tweets
Route::controller(TweetController::class)->group(function () {
    Route::get('users-tweets/{user_id}', 'tweetsOfUser');
    Route::post('users-tweets', 'store')->middleware('auth:sanctum');
    Route::put('users-tweets/{id}', 'update')->middleware('auth:sanctum');
    Route::delete('users-tweets/{id}', 'destroy')->middleware('auth:sanctum');
    Route::get('users-tweets-following', 'tweetsOffollowings')->middleware('auth:sanctum');
});

