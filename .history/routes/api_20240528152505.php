<?php

use App\Events\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\User\MeController;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

// Route::post('user/login',[LoginController::class, 'authenticate']);
// Route::post('user/login',[UserController::class, 'login']);

Route::post('login',LoginController::class);
Route::post('register', RegisterController::class);
Route::post('verify-email', VerifyEmailController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [MeController::class, 'show']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::resource('category', CategoryController::class)->except(['create', 'edit']);
//     Route::resource('post', PostController::class)->except(['create', 'edit']);
// });

// Route::post('user/logout',[UserController::class, 'logout'])->middleware('auth:sanctum');
// Route::post('user/token-check',[UserController::class, 'checkToken']);

// Route::post('email', function() {
//     Mail::to('teste@teste.com')->send(new WelcomeMail);
// });

// Route::post('email', function() {
//     UserRegistered::dispatch();
// });
