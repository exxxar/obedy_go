<?php

namespace App\Contracts;

use App\Models\Order;

interface PrepareChequeContract
{
    public function __invoke(Order $order) : array;
}
