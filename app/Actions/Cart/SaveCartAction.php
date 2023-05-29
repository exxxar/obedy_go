<?php

namespace App\Actions\Cart;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SaveCartAction
{

    public function __invoke($product, $action, $phone = null): void
    {
        if (is_null($phone))
            $user = User::find(Auth::id());
        else
            $user = User::where('phone', $phone)->first();
        $inCart = $user->inCart($product);
        $productId = $product['product']['id'];
        $pivot = $product['users'];
        switch ($action) {
            case 'add':
            case 'addMore':
            {
                if ($inCart) {
                    $existProduct = $user->products()->find($productId);
                    $users = json_decode($existProduct->pivot->users, true);
                    foreach ($pivot as $item) {
                        $isExist = false;
                        foreach ($users as $index => $userData) {
                            if ($userData['phone'] == $item['phone']) {
                                if($action == 'addMore' || $item['date'] > $userData['date']){
                                    $userData['quantity'] += $item['quantity'];
                                    $userData['date'] = $item['date'];
                                    $users[$index] = $userData;
                                }
                                $isExist = true;
                                break;
                            }
                        }
                        if (!$isExist) {
                            $users[] = $item;
                        }
                    }
                    $user->products()->updateExistingPivot($productId, ['users' => json_encode($users)]);
                } else
                    $user->products()->attach($productId, ['users' => json_encode($pivot)]);

                break;
            }
            case 'inc':
            case 'dec':
            {
                if ($inCart)
                    $user->products()->updateExistingPivot($productId, ['users' => json_encode($pivot)]);

                break;
            }
            case 'delete':
            {
                if ($inCart)
                    $user->products()->detach($productId);

                break;
            }
        }

    }

}
