<?php

namespace App\Actions;

use App\Models\Order;
use Carbon\Carbon;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendTelegramChequeAction
{

    public function __invoke(Order $order, $total_weight, $total_count)
    {
        Telegram::sendDocument([
            'chat_id' => config('telegram.channel'),
            'document' => InputFile::create(storage_path('app/public') . "/order-$order->phone.pdf"),
            'parse_mode' => "Markdown",
            'caption' => sprintf((
                "*Заявка ОбедыGO:*\n" .
                "Время заказа: *%s* \n" .
                "Номер телефона: *%s*\n" .
                "Имя покупателя: *%s*\n" .
                "Адрес доставки: *%s*\n" .
                "Сообщение от пользователя: *%s*\n" .
                "Суммарная масса продукции: *%s* гр.\n" .
                "Суммарная цена продукции: *%s* руб.\n" .
                "Суммарное число порций: *%s* ед.\n" .
                "Примерная цена доставки: *%s* руб. (%s км)"

            ),
                Carbon::now("+3:00"),
                $order->phone,
                $order->name,
                $order->address,
                $order->text,
                $total_weight,
                $order->summary_price,
                $total_count,
                $order->delivery_price,
                $order->delivery_range
            ),
        ]);
    }

}
