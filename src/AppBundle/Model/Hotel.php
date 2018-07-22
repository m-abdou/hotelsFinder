<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation as Serializer;

class Hotel
{
    /**
     * @var string
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     */
    private $name;
    /**
     * @var float
     * @Serializer\SerializedName("price")
     * @Serializer\Type("float")
     */
    private $price;
    /**
     * @var string
     * @Serializer\SerializedName("city")
     * @Serializer\Type("string")
     */
    private $city;
    /**
     * @var array
     * @Serializer\SerializedName("availability")
     * @Serializer\Type(name="array")
     */
    private $availability;

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice():float
    {
        return $this->price;
    }
    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity():string
    {
        return $this->city;
    }
    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return array
     */
    public function getAvailability():array
    {
        return $this->availability;
    }
    /**
     * @param array $availability
     * @return $this
     */
    public function setAvailability(array $availability)
    {
        $this->availability = $availability;
        return $this;
    }
}
