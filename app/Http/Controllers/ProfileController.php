<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;

        $user->save();

        return response()->json(['message' => 'Profil başarıyla güncellendi!']);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages(['current_password' => 'Mevcut şifre hatalı.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Şifre başarıyla güncellendi!']);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['password' => 'Şifre hatalı.']);
        }

        $user->delete();

        return response()->json(['message' => 'Hesap başarıyla silindi!']);
    }

    public function updateProfilePicture(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Eski profil fotoğrafını sil (varsa)
        if ($user->profile_picture) {
            Storage::delete('public/profile_pictures/' . $user->profile_picture);
        }

        // Resmi yükle ve kaydet
        $file = $request->file('profile_picture');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/profile_pictures', $filename);


        // Kullanıcıyı güncelle
        $user->profile_picture = $filename;
        $user->save();

        return response()->json(['success' => true, 'photoUrl' => Storage::url($user->profile_picture)]);
    }


}
