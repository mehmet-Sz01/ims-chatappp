<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Http\Resources\MessagesResource;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Chat $chat)
    {
        $query = $request->input('query', '');
        $messages = $chat->messages()
            ->with('user')
            ->latest()
            ->get();

        $user = auth()->user();

        $chats = $query ? Chat::search($query)
            ->get() : Chat::whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('users')->get();

        return response()->json([
            'chat' => new ChatResource($chat),
            'chats' => ChatResource::collection($chats),
            'messages' => MessagesResource::collection($messages),
        ]);
    }
}
