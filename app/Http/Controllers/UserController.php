<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        // Mevcut kullanıcıyı hariç tutarak diğer tüm kullanıcıları al

        $users = User::where('id', '!=', auth()->id())->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
