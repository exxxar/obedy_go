<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Events\SeenAllMessagesEvent;
use App\Events\SeenMessageEvent;
use App\Http\Requests\Chats\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function getChats()
    {
        $userId = auth()->id();
        $sql = "(SELECT `created_at`
FROM `messages`
WHERE `messages`.`chat_id` = `chats`.`id`
ORDER BY `messages`.`created_at` DESC
LIMIT 1)";

        $chats = Chat::with('user', 'specialist', 'lastMessage')
            ->where('user_id', $userId)
            ->orWhere('specialist_id', $userId)
            ->orderBy(DB::raw($sql), 'desc')
            ->get();
        return response()->json(ChatResource::collection($chats));
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
        $chat->messages()->where('receiver_id', $userId)->update(['is_seen' => true]);
        $receiver_id = $chat->user_id == $userId ? $chat->specialist_id : $chat->user_id;
        broadcast(new SeenAllMessagesEvent($chat->id, $receiver_id));
        return response()->json((new ChatResource($chat))->content(null, true));
    }

    public function sendMessage(MessageRequest $request)
    {
        $images = [];
        $files = [];
        $chat = Chat::find($request->chatId);
        $imageRules = array(
            'image' => 'image'
        );
        if($request->hasFile('files')){
            foreach ( $request->file('files') as $file) {
                $image = array('image' => $file);
                $imageValidator = Validator::make($image, $imageRules);
                $extension = $file->getClientOriginalExtension();
                $name = "message-obedy-" . uniqid() . '.'. $extension;
                if($imageValidator->fails()){
                    $files[] = ['path'=>"storage/chats/chat-$chat->id/$name", 'name'=>$file->getClientOriginalName()];
                }else{
                    $images[] = "storage/chats/chat-$chat->id/$name";
                }
                Storage::disk('public')->put("chats/chat-$chat->id/$name", File::get($file));
            }
        }
        $receiver_id = $chat->user_id == auth()->id() ? $chat->specialist_id : $chat->user_id;
        $message = Message::create([
            'chat_id' => $request->chatId,
            'message' => $request->message,
            'receiver_id'=> $receiver_id,
            'files' => $files,
            'images' => $images
        ]);
        $chat->lastMessage = $message;

        broadcast(new MessageSentEvent($receiver_id, (new ChatResource($chat))->content($receiver_id)));

        return response()->json(new ChatResource($chat));

    }

    public function seenMessage($id){
        $userId = auth()->id();
        $message  = Message::with('chat')->where(['id'=>$id, 'receiver_id' => $userId])->first();
        if($message) {
            $message->is_seen = true;
            $message->save();
            $receiver_id = $message->chat->user_id == $userId ? $message->chat->specialist_id : $message->chat->user_id;
            broadcast(new SeenMessageEvent($message->chat_id, $message->id, $receiver_id));
        }
        return response(null, 200);
    }
}
