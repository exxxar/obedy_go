<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'description'=>$this->description,
            'price'=>$this->price,
            'image'=> is_null($this->image) ? config('app.logo') : $this->image,
            $this->mergeWhen( $request->route()->getName() == 'special', [
                'specialist'=> new ProfileResource($this->user),
                'slug'=>$this->slug,
            ]),
            $this->mergeWhen( $request->route()->getName() == 'menu.edit', [
                'products'=> ProductResource::collection($this->products),
            ])
        ];
    }
}
