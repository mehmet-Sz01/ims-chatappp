<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/friends', [FriendController::class, 'getFriends']);
Route::get('/users', [UserController::class, 'getUsers']);
Route::post('/messages', [MessageController::class, 'sendMessage']);


Route::middleware('auth:api')->group(function () {
    Route::get('/user', [ProfileController::class, 'getProfile']);
    Route::put('/user', [ProfileController::class, 'updateProfile']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/user', [ProfileController::class, 'deleteAccount']);
});
