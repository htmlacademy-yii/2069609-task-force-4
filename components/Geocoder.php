<?php

namespace app\components;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

class Geocoder extends \yii\base\Component
{
    public $apiKey;

    public function getAddress(){
        //напишу после первой проверки
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getPoint($address){

        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/',
        ]);
        try {
            $response = $client->request('GET', '1.x', [
                'query' =>
                    [
                        'geocode' => $address,
                        'format' => 'json',
                        'result' => 1,
                    ]
            ]);
            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);
            $addressPlace = ArrayHelper::getValue($response_data, 'response.GeoObjectCollection.featureMember');
            $latLong = explode(' ', ArrayHelper::getValue($addressPlace, 'GeoObject.Point.pos'));
            $result = [
                'lat' => $latLong['0'],
                'long' => $latLong['1'],
            ];
        } catch (RequestException $e) {
            $result = [];
        }
        return $result;
    }
}