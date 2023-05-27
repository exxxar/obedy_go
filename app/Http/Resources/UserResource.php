<?php

namespace App\Http\Resources;

use App\Models\User;
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
            $authUser = User::find(Auth::id());
            foreach ($authUser->products as $product) {
                $cartItem = [
                    'product' => new ProductResource($product),
                    'users' => json_decode($product->pivot->users, true),
                ];
                $cart[] = $cartItem;
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
