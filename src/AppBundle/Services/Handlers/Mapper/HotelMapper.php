<?php

namespace AppBundle\Services\Handlers\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use AppBundle\Model\Hotel;

class Hotel
{

    /** @var Serializer $serializer */
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * convert json object to array of collection typ hotel
     * @param $jsonResponse
     * @return ArrayCollection
     */
    public function convertJsonToArrayCollection($jsonResponse):ArrayCollection
    {
        return $this->serializer->deserialize($jsonResponse, Hotel::class, 'json');
    }

    /**
     * convert array of objects to array
     * @param ArrayCollection $collection
     * @return array
     */
    public function convertCollectionToArray(ArrayCollection $collection):array
    {
        return array_values(json_decode($this->serializer->serialize($collection, 'json'), true));
    }

}