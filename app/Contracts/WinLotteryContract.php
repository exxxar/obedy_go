<?php

namespace App\Contracts;

use App\Models\Lottery;

interface WinLotteryContract
{
    public function __invoke(Lottery $lottery, array $promocode, int $index) : void;
}
