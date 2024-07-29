<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Request\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {

        $userControl = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($userControl)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'message' => 'Giriş Başarılı',
                'token' => $token,
                'user' => $user,
            ], 200);
        } else {
            return response()->json('Giriş Başarısız', 401);
        }
    }
    public function logout(): JsonResponse
    {
        Session::flush();
        return response()->json('Çıkış İşlemi Başarılı');
    }
}
