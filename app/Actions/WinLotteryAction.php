<?php

namespace App\Actions;

use App\Contracts\WinLotteryContract;
use App\Events\SendSmsEvent;
use App\Mail\LotteryMail;
use App\Models\Lottery;
use App\Models\LotteryPromocode;
use Illuminate\Support\Facades\Mail;

class WinLotteryAction implements WinLotteryContract
{

    public function __invoke(Lottery $lottery, $promocode, $index): void
    {
        $message = sprintf('Вы заняли #%s место в розыгрыше %s', $index, $lottery->title);

        event(new SendSmsEvent($promocode->phone, $message));

       // Mail::to($promocode->email)->send(new LotteryMail($message));

        if ($lottery->free_place_count == 0) {
            $place = LotteryPromocode::where("lottery_id", $lottery->id)
                ->get()
                ->random(1)
                ->first();

            $lottery->win_promo_id = $place->id;
            $lottery->is_active = false;
            $lottery->is_complete = true;
            $lottery->save();


            $message = sprintf("Ваш #%s в розыгрыше %s оказался выигрышным! За деталями обратитесь к администратору сервиса.", $index, $lottery->title);

            event(new SendSmsEvent($promocode->phone, $message));

           // Mail::to($promocode->email)->send(new LotteryMail($message));
        }

    }

}
