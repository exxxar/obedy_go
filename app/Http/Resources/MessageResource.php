<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    protected $user_id;

    public function content($user_id = null)
    {
        $this->user_id = $user_id;
        return $this;
    }


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = is_null($this->user_id) ? auth()->id() : $this->user_id;
        return [
            'id'=>$this->id,
            'message'=>$this->message,
            'images'=>$this->images,
            'files'=>$this->files,
            'isUserMessage'=>$this->receiver_id != $userId,
            'date'=>Carbon::parse($this->created_at)->setTimezone('Europe/Moscow')->format('d.m.Y'),
            'time'=>Carbon::parse($this->created_at)->setTimezone('Europe/Moscow')->format('H:i'),
            'isSeen'=>$this->is_seen
        ];
    }
}
