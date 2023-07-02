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
        $user = auth()->user();
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'price'=>$this->price,
            'image'=> is_null($this->image) ? config('app.logo') : $this->image,
            'isUserMenu'=> $user && (($user->purchasedMenus()->where('menu_id', $this->id)->exists() || $this->price == 0)),
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
