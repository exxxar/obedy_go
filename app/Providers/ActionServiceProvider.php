<?php

namespace App\Providers;

use App\Actions\CalculateDeliveryDistanceAction;
use App\Actions\PrepareChequeAction;
use App\Actions\WinLotteryAction;
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
