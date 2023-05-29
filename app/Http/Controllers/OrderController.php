<?php

namespace App\Http\Controllers;

use App\Actions\Cart\SaveCartAction;
use App\Actions\Confirm\ConfirmCodeAction;
use App\Actions\Order\OrderChequeGroupAction;
use App\Contracts\CalculateDeliveryDistanceContract;
use App\Http\Requests\Order\CheckOrderRequest;
use App\Http\Requests\Order\DeliveryRangeRequest;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\YandexService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function getDeliveryRange(DeliveryRangeRequest $request, CalculateDeliveryDistanceContract $calculateDeliveryDistanceAction, YandexService $yandexService)
    {
        $coords = $yandexService->getCoordsByAddress($request->address);
        $range = $calculateDeliveryDistanceAction($coords);

        $price = $range <= 3 ?
            config('delivery.baseDeliveryPrice') :
            ceil((config('delivery.baseDeliveryPrice') + $range * config('delivery.baseDeliveryPricePerKM')));

        return response()
            ->json([
                'range' => floatval(sprintf('%.2f', ($range <= 2 ? $range : ($range + 2)))),
                'price' => $price,
                'latitude' => $coords['latitude'],
                'longitude' => $coords['longitude']
            ]);

    }

    public function order(OrderRequest $request, OrderChequeGroupAction $chequeGroupAction)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user && !Auth::check()) {
            if (!$request->code) {
                (new ConfirmCodeAction())($request->phone);
                return response([], 406);
            }
        }

        if($user){
            $user->name = $request->name;
            $user->addresses = $request->addresses;
            $user->save();
            $user->products()->detach();
        }
        if ($request->manager_phone){
            foreach ($request->products as $product){
                foreach($product['users'] as $index => $user){
                    if(!array_key_exists('phone', $user) || $user['phone'] == null){
                        $user['phone'] = $request->phone;
                        $user['name'] = $request->name;
                        $user['inDBCart'] = true;
                        $product['users'][$index] = $user;
                    }
                }
                (new saveCartAction())($product, 'add', $request->manager_phone);
            }
            return response([], 409);
        }

        $order = Order::create([
            'number' => Str::uuid(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'text' => $request->message,
            'delivery_price' => $request->delivery_price,
            'delivery_range' => $request->delivery_range
        ]);

        foreach ($request->products as $item) {
            $quantity = 0;
            foreach ($item['users'] as $user){
                $quantity+=(int)$user['quantity'];
            }
            $order->products()
                ->attach($item['product']['id'], ['quantity' => $quantity]);
        }

        return  $chequeGroupAction($order);

    }

    public function resendCode(ResendCodeRequest $request, ConfirmCodeAction $confirmCodeAction)
    {
        $confirmCodeAction($request->phone);
        return response()->json(['status' => 'ok']);
    }

    public function checkOrder(CheckOrderRequest $request){

        $order = Order::where('number', $request->number)->first();

        return response()->json(['status'=>$order->status ]);

    }

}
