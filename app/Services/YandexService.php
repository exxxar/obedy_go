<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YandexService
{

    public function getCoordsByAddress($address)
    {
        $apiKey = config('yandex.api_key');

        $response = Http::get('https://geocode-maps.yandex.ru/1.x/', [
            'apikey' => $apiKey,
            'format' => 'json',
            'geocode' => str_replace(' ', '+', $address)
        ]);

        $data = json_decode($response, true);

        $count = $data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found'];
        $coords = ['latitude'=> 0, 'longitude'=>0];

        if ($count == 1 && isset($data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
            $tmp_coords = explode(' ', $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);
            $coords['latitude'] = $tmp_coords[1];
            $coords['longitude'] = $tmp_coords[0];
        }

        return $coords;
    }

}
