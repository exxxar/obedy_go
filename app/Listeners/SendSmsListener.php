<?php

namespace App\Listeners;

use App\Events\SendSmsEvent;

use App\Libraries\SmsRu\SMSRU;

use stdClass;

class SendSmsListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendSmsEvent $event): void
    {
        $smsru = new SMSRU(config('sms-ru.api_key'));

        $data = new stdClass();
        $data->to = $event->phone;
        $data->text = $event->text;
        $data->from = config('sms-ru.from'); // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        $data->translit = config('sms-ru.translit'); // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        $data->test = config('sms-ru.test');

        $sms = $smsru->send_one($data);

        if ($sms->status != "OK") {
            $data->time = time() + 120; // Отложить отправку на 2 минуты
            $sms = $smsru->send_one($data);
        }
    }
}
