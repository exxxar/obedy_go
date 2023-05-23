<?php

namespace App\Providers;

use App\Actions\Lottery\WinLotteryAction;
use App\Actions\Order\CalculateDeliveryDistanceAction;
use App\Actions\Order\PrepareChequeAction;
use App\Contracts\CalculateDeliveryDistanceContract;
use App\Contracts\PrepareChequeContract;
use App\Contracts\WinLotteryContract;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings = [
        WinLotteryContract::class => WinLotteryAction::class,
        CalculateDeliveryDistanceContract::class => CalculateDeliveryDistanceAction::class,
        PrepareChequeContract::class => PrepareChequeAction::class
    ];

}
