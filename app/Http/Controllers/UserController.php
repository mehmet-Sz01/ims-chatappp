<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUsers()
    {
        // Arkadaş olmayan kullanıcıları döndür
        $users = User::whereDoesntHave('friends', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return response()->json($users);
    }
}
