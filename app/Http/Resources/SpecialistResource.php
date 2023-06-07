<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'image'=> is_null($this->image) ? config('app.logo') : $this->image,
            'description'=>$this->description,
            'isCurrentUser'=>auth()->id() == $this->id,
        ];
    }
}
