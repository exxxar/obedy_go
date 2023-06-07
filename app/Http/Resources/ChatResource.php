<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = auth()->id();
        $messageCount = 0;
        $user = [];
        if($request->route()->named('chat.messages')) {
            $user = [
                'name' => $this->user->name,
                'image' => is_null($this->user->image) ? config('app.logo') : $this->user->image
            ];
            $messageCount = $this->messages->count();
        }
        $specialist = [
            'name'=>$this->specialist->name,
            'image'=>is_null($this->specialist->image) ? config('app.logo') : $this->specialist->image,
        ];
        return [
            'id'=>$this->id,
            'interlocutor'=>$this->specialist_id != $userId ? $specialist : $user,
            $this->mergeWhen($request->route()->named('chat.messages'), [
                'user'=>$this->user_id == $userId ? $user : $specialist,
                'messages'=> MessageResource::collection($this->messages),
                'messageCount'=>$messageCount
            ]),
            $this->mergeWhen($request->route()->named('chats'), [
                'lastMessage'=>$this->lastMessage ? (is_null($this->lastMessage->message) ? 'Вложения' : $this->lastMessage->message) : null
            ]),
        ];
    }
}
