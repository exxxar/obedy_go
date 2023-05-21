<?php

namespace App\Actions;

use App\Contracts\CalculateDeliveryDistanceContract;
use App\Services\YandexService;

class CalculateDeliveryDistanceAction implements CalculateDeliveryDistanceContract
{
    /**
     * @var int
     */
    protected int $earth_radius = 6372795;


    public function __invoke(array $coords): float
    {

        $obedygLatitude = config('delivery.latitude');
        $obedygLongitude = config('delivery.longitude');

        // перевести координаты в радианы
        $lat1 = $coords['latitude'] * M_PI / 180;
        $lat2 = $obedygLatitude * M_PI / 180;
        $long1 = $coords['longitude'] * M_PI / 180;
        $long2 = $obedygLongitude * M_PI / 180;

        // косинусы и синусы широт и разницы долгот
        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        // вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        $ad = atan2($y, $x);
        $range = ($ad * $this->earth_radius) / 1000;

        return ($range < 10 ? $range + 2 : $range + 7);

    }

}
