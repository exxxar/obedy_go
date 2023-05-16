<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotteryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'place_count'  => $this->place_count,
            'free_place_count' => $this->free_place_count,
            'image' => $this->image,
            $this->mergeWhen($request->route()->named('lottery'), [
                'title'=>$this->title,
                'is_active'=>$this->is_active,
                'date_end'=>Carbon::parse($this->date_end)->format('d.m.Y'),
                'is_complete'=>$this->is_complete,
                'description'=>$this->description,
                'occupied_places'=>$this->occupied_places
            ])
        ];
    }
}
