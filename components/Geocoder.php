<?php

namespace app\components;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Client;

class Geocoder extends Component
{
    const KEY_POINT = 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos';
    const KEY_ADDRESS = 'response.GeoObjectCollection.featureMember.0.GeoObject.name';

    public string $apiKey;
    public string $baseUri;
    public Client $client;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->client = new Client(['base_uri' => $this->baseUri]);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getAddress($longlat){
            $response = $this->client->request('GET', '1.x', [
                'query' =>
                    [
                        'apikey' => $this->apiKey,
                        'geocode' => $longlat,
                        'format' => 'json',
                        'result' => 1,
                    ]
            ]);

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);
            return ArrayHelper::getValue($response_data, self::KEY_ADDRESS);
    }
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getPoint($address){
        try {
            $response = $this->client->request('GET', '1.x', [
                'query' =>
                    [
                        'apikey' => $this->apiKey,
                        'geocode' => $address,
                        'format' => 'json',
                        'result' => 1,
                    ]
            ]);

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);
            $addressPlace = ArrayHelper::getValue($response_data, self::KEY_POINT);
            $latLong = explode(' ', $addressPlace);
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