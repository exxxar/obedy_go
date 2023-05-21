<?php

namespace App\Actions;

use App\Contracts\PrepareChequeContract;
use App\Events\SendSmsEvent;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class OrderChequeGroupAction
{

    public function __construct(
        private PrepareChequeContract $prepareChequeAction,
        private SendTelegramChequeAction $sendTelegramChequeAction
    )
    {}

    public function __invoke(Order $order)
    {
        $result = ($this->prepareChequeAction)($order);

        event(new SendSmsEvent($order->phone, "Мы получили вашу заявку на заказ из сервиса ОбедыGO!"));

        ($this->sendTelegramChequeAction)($order, $result['total_weight'], $result['total_count']);

        Storage::disk('public')->delete("order-$order->phone.pdf");

        return $result['cheque'];

    }

}
