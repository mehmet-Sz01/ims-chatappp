<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();
        // Başarılı yanıt gönder
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'data' => $message
        ]);
    }

    public function getMessages($friendId)
    {
        $messages = Message::where(function($query) use ($friendId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $friendId);
        })->orWhere(function($query) use ($friendId) {
            $query->where('sender_id', $friendId)
                ->where('receiver_id', Auth::id());
        })->get();

        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function deleteMessages($friendId)
    {
        try {
            $userId = Auth::id();

            // Kullanıcı tarafından silinen mesajları işaretle
            Message::where('sender_id', $userId)
                ->where('receiver_id', $friendId)
                ->update(['deleted_by_sender' => true]);

            // Alıcı tarafından silinen mesajları işaretle
            Message::where('sender_id', $friendId)
                ->where('receiver_id', $userId)
                ->update(['deleted_by_receiver' => true]);

            return response()->json(['success' => true, 'message' => 'Mesajlar başarıyla silindi.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Mesajlar silinirken hata oluştu.', 'error' => $e->getMessage()], 500);
        }
    }


}
