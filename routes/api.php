<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout']);

    Route::post('/add-friend', [FriendController::class, 'addFriend']);
    Route::get('/friends', [FriendController::class, 'getFriends']);
    Route::post('/remove-friend', [FriendController::class, 'removeFriend']);

    Route::get('/users', [UserController::class, 'getUsers']);

    Route::get('/messages/{friendId}', [MessageController::class, 'getMessages']);
    Route::post('/messages', [MessageController::class, 'sendMessage']);
    Route::delete('/messages/{friendId}', [MessageController::class, 'deleteMessages']);

    Route::get('/user', [ProfileController::class, 'getProfile']);
    Route::put('/user', [ProfileController::class, 'updateProfile']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);
    Route::post('/user/profile-picture', [ProfileController::class, 'updateProfilePicture']);
    Route::delete('/user', [ProfileController::class, 'deleteAccount']);
});
