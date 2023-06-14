<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{

    protected $receiverId;
    protected $isChatMessages;

    public function content($receiverId=null, $isChatMessages=false)
    {
        $this->receiverId = $receiverId;
        $this->isChatMessages = $isChatMessages;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = is_null($this->receiverId) ? auth()->id() : $this->receiverId;
        $user = [
            'name' => $this->user->name,
            'image' => is_null($this->user->image) ? config('app.logo') : $this->user->image
        ];
        $specialist = [
            'name'=>$this->specialist->name,
            'image'=>is_null($this->specialist->image) ? config('app.logo') : $this->specialist->image,
        ];
        $messages = [];
        $messageCount = 0;
        if($this->isChatMessages){
            $messages = MessageResource::collection($this->messages()->simplePaginate(20));
            $messageCount = $this->messages->count();
        }
        $lastMessage = null;
        $unseenMessageCount = 0;
        if(!$this->isChatMessages){
            $lastMessage = $this->lastMessage ? (new MessageResource($this->lastMessage))->content($this->receiverId) : null;
            $unseenMessageCount = $this->messages()->where(['is_seen'=> false, 'receiver_id'=>$userId])->count();
        }
        return [
            'id'=>$this->id,
            'interlocutor'=>$this->specialist_id != $userId ? $specialist : $user,
            $this->mergeWhen($this->isChatMessages, [
                'user'=>$this->user_id == $userId ? $user : $specialist,
                'messages'=> $messages,
                'messageCount'=>$messageCount
            ]),
            'unseenMessageCount'=> $unseenMessageCount,
            $this->mergeWhen(!$this->isChatMessages, [
                'lastMessage'=> $lastMessage,
            ]),
        ];
    }
}
