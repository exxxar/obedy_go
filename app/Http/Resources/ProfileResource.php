<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=>$this->name,
            'image'=> is_null($this->image) ? config('app.logo') : $this->image,
            'description'=>$this->description,
            $this->mergeWhen($request->route()->getName() == 'profile.get', [
                'addresses'=> is_null($this->addresses) ? [] : $this->addresses,
                'phone'=>$this->phone,
                'isSpecialist'=>$this->hasRole('specialist')
            ]),
            $this->mergeWhen(in_array($request->route()->getName(), ['specialists', 'special']), [
                'id'=>$this->id,
                'isCurrentUser'=>auth()->id() == $this->id,
            ])
        ];
    }
}
