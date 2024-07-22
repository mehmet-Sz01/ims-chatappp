<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {

        // İstekten gelen verileri doğrula
        $validator = Validator::make($request->all(), [
            'sender_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required|integer|exists:users,id',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Mesajı oluştur ve veritabanına kaydet
        $message = Chat::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Başarılı yanıt gönder
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => $message
        ]);
    }
}
