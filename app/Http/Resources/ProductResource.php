<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title'=>$this->title,
            'image'=>$this->image,
            'description'=>$this->description,
            'positions'=>$this->positions,
            'price'=>$this->price,
            'weight'=>$this->weight,
            'is_week'=>$this->is_week,
        ];

    }
}
