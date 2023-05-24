<?php

namespace App\Http\Controllers;

use App\Actions\Confirm\ConfirmCodeAction;
use App\Actions\Order\OrderChequeGroupAction;
use App\Contracts\CalculateDeliveryDistanceContract;
use App\Events\SendSmsEvent;
use App\Http\Requests\Order\DeliveryRangeRequest;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Services\YandexService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
            $order->products()
                ->attach($item['product']['id'], ['quantity' => $item['quantity']]);
            if(Auth::check()){
                $user->products()->detach($item['product']['id']);
            }
        }

        return  $chequeGroupAction($order);

    }

    public function resendCode(ResendCodeRequest $request, ConfirmCodeAction $confirmCodeAction)
    {
        $confirmCodeAction($request->phone);
        return response()->json(['status' => 'ok']);
    }

}
