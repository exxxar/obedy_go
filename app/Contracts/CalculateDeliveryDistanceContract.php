<?php

namespace App\Contracts;

interface CalculateDeliveryDistanceContract
{
    public function __invoke(array $coords) : float;
}
