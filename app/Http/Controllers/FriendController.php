<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function getFriends()
    {
        // Mevcut kullanıcıya ait arkadaşları alın
        // Örnek: arkadaş ilişkisini 'friends' olarak tanımladığınızı varsayıyoruz.
        $friends = auth()->user()->friends;

        return response()->json([
            'success' => true,
            'data' => $friends
        ]);
    }

}
