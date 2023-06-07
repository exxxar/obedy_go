<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chats\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function getChats()
    {
        $userId = auth()->id();
        $chats = Chat::with('user', 'specialist', 'lastMessage')
            ->where('user_id', $userId)
            ->orWhere('specialist_id', $userId)
            ->get();
        return Inertia::render('Chats', [
            'chats' => ChatResource::collection($chats)
        ]);
    }

    public function createChat($id)
    {
        if (!User::role('specialist')->where('id', $id)->exists())
            return response(null, 404);
        $chat = Chat::where(['user_id' => auth()->id(), 'specialist_id' => $id])->first();
        if (!$chat)
            $chat = Chat::create(['user_id' => auth()->id(), 'specialist_id' => $id]);
        return response()->json(['chatId' => $chat->id]);

    }

    public function getMessages($id)
    {
        $chat = Chat::with('user', 'specialist', 'messages')->find($id);
        $userId = auth()->id();
        if (!$chat)
            return response(null, 404);
        if ($chat->user_id != $userId && $chat->specialist_id != $userId)
            return response(null, 403);
        return response()->json(new ChatResource($chat));
    }

    public function sendMessage(MessageRequest $request)
    {

    }
}
