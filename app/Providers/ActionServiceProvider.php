<?php

namespace App\Providers;

use App\Actions\WinLotteryAction;
use App\Contracts\WinLotteryContract;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings = [
        WinLotteryContract::class => WinLotteryAction::class,
    ];

}
