<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // Arkadaş ekleme fonksiyonu
    public function addFriend(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }

        // Giriş yapan kullanıcı kendisini arkadaş olarak ekleyemez
        $currentUser = Auth::user();
        if ($currentUser->id == $userId) {
            return response()->json(['success' => false, 'message' => 'You cannot add yourself as a friend']);
        }

        // Kullanıcının zaten arkadaş olup olmadığını kontrol et
        if ($currentUser->friends->contains($userId)) {
            return response()->json(['success' => false, 'message' => 'Friend already added']);
        }

        // Arkadaş ekleme işlemi
        $currentUser->friends()->attach($userId);

        return response()->json(['success' => true, 'message' => 'Friend added successfully']);
    }


    // Arkadaş silme fonksiyonu
    public function removeFriend(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }

        // Arkadaşın mevcut olup olmadığını kontrol et
        $currentUser = Auth::user();
        if (!$currentUser->friends->contains($userId)) {
            return response()->json(['success' => false, 'message' => 'Friend not found']);
        }

        // Arkadaşı silme işlemi
        $currentUser->friends()->detach($userId);

        return response()->json(['success' => true, 'message' => 'Friend removed successfully']);
    }

    // Kullanıcıların arkadaşlarını listeleme fonksiyonu
    public function getFriends()
    {
        $friends = Auth::user()->friends;

        return response()->json([
            'success' => true,
            'data' => $friends
        ]);
    }
}
