<?php

namespace AppBundle\Services\Handlers\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializerBuilder;

class HotelMapper
{

    private $serializer;

    /**
     * @var ArrayCollection
     * @Serializer\SerializedName("hotels")
     * @Serializer\Type(name="ArrayCollection<AppBundle\Model\Hotel>")
     */
    private $hotels;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * convert json object to array of collection type hotel
     * @param $jsonResponse
     * @return ArrayCollection
     */
    public function convertJsonToArrayCollection($jsonResponse):ArrayCollection
    {
        $this->hotels = $this->serializer->deserialize($jsonResponse, HotelMapper::class, 'json')->hotels;

        return $this->hotels;
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