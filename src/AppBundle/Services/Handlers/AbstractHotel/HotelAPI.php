<?php
/**
 * Created by PhpStorm.
 * User: abdou
 * Date: 7/22/18
 * Time: 11:20 AM
 */

namespace AppBundle\Services\Handlers\AbstractHotel;

use GuzzleHttp\Client as Client;

abstract class AbstractHotelAPI
{

    /**
     * responsible for fetch data from external api
     * @return array
     */
    public function fetch():array
    {
        $client = new Client();
        $url = $this.$this->getApiUrl();
        $response = $client->get($url);
        $response = json_decode($response->getBody(), true);
        return $this.$this->convertResponseToObject($response);
    }

    protected abstract function getApiUrl():string;
}
