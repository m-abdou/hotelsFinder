<?php

namespace AppBundle\Services\Handlers\AbstractHotel;

use GuzzleHttp\Client as Client;

abstract class HotelAPI
{

    protected abstract function getApiUrl():string;

    /**
     * responsible for fetch data from external api
     * @return json object
     */
    public function fetch()
    {
        $client = new Client();
        $url = $this->getApiUrl();
        $response = $client->get($url);
        return $response->getBody();
    }

}
