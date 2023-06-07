<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message'=>$this->message,
            'images'=>$this->images,
            'files'=>$this->files,
            'isUserMessage'=>$this->receiver_id != auth()->id(),
            'date'=>Carbon::parse($this->created_at)->format('d.m.Y'),
            'time'=>Carbon::parse($this->created_at)->format('H:i'),
        ];
    }
}
