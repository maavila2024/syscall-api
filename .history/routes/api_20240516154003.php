<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;

// Route::post('user/login',[LoginController::class, 'authenticate']);
// Route::post('user/login',[UserController::class, 'login']);

Route::post('login',LoginController::class);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::resource('category', CategoryController::class)->except(['create', 'edit']);
//     Route::resource('post', PostController::class)->except(['create', 'edit']);
// });

// Route::post('user/logout',[UserController::class, 'logout'])->middleware('auth:sanctum');
// Route::post('user/token-check',[UserController::class, 'checkToken']);
