<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $authCheck = Auth::check();
        $cart = [];
        $user = null;
        if($authCheck) {
            foreach ($this->products as $product) {
                $cart['product'] = new ProductResource($product);
                $cart['quantity'] = $product->pivot->quantity;
                $cart['name'] = $product->pivot->name;
                $cart['phone'] = $product->pivot->phone;
            }
            $user =  [
                'name'=>$this->name,
                'phone'=>$this->phone,
                'addresses'=>$this->addresses
            ];
        }
        return [
            'user'=> $user,
            'cart'=> $cart,
            "token"=>csrf_token()
        ];
    }
}
