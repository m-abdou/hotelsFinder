<?php

namespace AppBundle\Services\Handlers;

use AppBundle\Model\Hotel;
use AppBundle\Services\Handlers\AbstractHotel\HotelAPI;
use AppBundle\Services\Handlers\Interfaces\HotelInterface;
use AppBundle\Services\Handlers\Mapper\HotelMapper;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;
use Doctrine\Common\Collections\Criteria;


class HotelHandler extends HotelAPI implements HotelInterface
{

    /**
     * @var ArrayCollection
     */
    private $hotels;

    /**
     * @var HotelMapper $hotelMapper;
     */
    private $hotelMapper;

    /** @var Criteria $criteria */
    private $criteria;

    private $sortBy = ['name' => Criteria::ASC, 'price' => Criteria::DESC];

    /**
     * HotelHandler constructor.
     * @param HotelMapper $hotelMapper
     */
    public function __construct(HotelMapper $hotelMapper)
    {
        $this->hotelMapper = $hotelMapper;
        $this->setHotels();
    }


    /**
     * initialize url data collector
     * @return string
     */
    protected function getAPIURL():string
    {
        return "http://api.myjson.com/bins/tl0bp";
    }

    public function setHotels()
    {
        if(!$this->hotels) {
            $this->hotels = $this->hotelMapper->convertJsonToArrayCollection($this->fetch());
        }
    }

    /**
     * return hotels
     * @return ArrayCollection
     */
    public function getHotels()
    {
        return $this->hotels;
    }

    public function searchByQuery($queryParam){
        $this->criteria = Criteria::create();

        if((isset($queryParam['available_from']) && !empty($queryParam['available_from']))
            && (isset($queryParam['available_from']) && !empty($queryParam['available_to']))
        ) {
            $this->searchByAvailability($queryParam['available_from'], $queryParam['available_to']);
        }

        if(isset($queryParam['name']) && !empty($queryParam['name'])) {
            $this->searchByName($queryParam['name']);
        }

        if(isset($queryParam['city']) && !empty($queryParam['city'])) {
            $this->searchByCity($queryParam['city']);
        }

        if((isset($queryParam['price_from']) && !empty($queryParam['price_from']))
            && (isset($queryParam['price_from']) && !empty($queryParam['price_to']))
        ) {
            $this->searchByPrice($queryParam['price_from'], $queryParam['price_to']);
        }

        if(isset($queryParam['sort_by']) && !empty($queryParam['sort_by'])) {
            $this->sortBy($queryParam['sort_by']);
        }


        $result = $this->hotelMapper->convertCollectionToArray($this->getHotels()->matching($this->criteria));

        return ['status' => true , 'hotels' => $result];
    }

    public function searchByAvailability(DateTime $availableFrom, DateTime $availableTo)
    {
        $from = $availableFrom->getTimestamp();
        $to = $availableTo->getTimestamp();

        if ($from > $to) {
            return null;
        }

        $this->hotels = $this->getHotels()->filter(
            function (Hotel $hotel) use ($from, $to) {
                foreach ($hotel->getAvailability() as $availability) {
                    $hotelAvailableFrom = strtotime($availability['from']);
                    $hotelAvailableTo = strtotime($availability['to']);

                    if ($from >= $hotelAvailableFrom &&
                        $from < $hotelAvailableTo &&
                        $to > $hotelAvailableFrom &&
                        $to <= $hotelAvailableTo
                    ) {
                        return $hotel;
                    }
                }

                return null;
        });

        return $this->hotels;
    }

    public function searchByName(string $name)
    {
        $condition = Criteria::expr()->eq('name', $name);
        $this->criteria->andWhere($condition);
    }

    public function searchByCity(string $city)
    {
        $condition = Criteria::expr()->eq('city', $city);
        $this->criteria->andWhere($condition);
    }

    public function searchByPrice(float $from, float $to)
    {
        $priceFromCondition = Criteria::expr()->gte('price', $from);
        $priceToCondition = Criteria::expr()->lte('price', $to);
        $this->criteria->andWhere($priceFromCondition)->andWhere($priceToCondition);
    }

    public function sortBy(string $sort)
    {
        if(key_exists($sort, $this->sortBy)){
            $this->criteria->orderBy([ $sort => $this->sortBy[$sort] ]);
        }
    }
}
