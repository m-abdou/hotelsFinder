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
        $this->hotels = $this->hotelMapper->convertJsonToArrayCollection($this->fetch());
    }


    /**
     * initialize url data collector
     * @return string
     */
    protected function getAPIURL():string
    {
        return "http://api.myjson.com/bins/tl0bp";
    }

    public function setHotels(ArrayCollection $hotels)
    {

        if(!$this->hotels || empty($hotels)) {
            $this->hotels = $this->hotelMapper->convertJsonToArrayCollection($this->fetch());
        }else {
            $this->hotels = $hotels;
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

    /**
     * provide search by query string
     * @param $queryParam
     * @return array
     */
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

    /**
     * provide search criteria by availability
     * @param DateTime $availableFrom
     * @param DateTime $availableTo
     * @return ArrayCollection|null
     */
    public function searchByAvailability(string $availableFrom, string $availableTo)
    {
        $from = strtotime($availableFrom);
        $to = strtotime($availableTo);

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
    }

    /**
     * provide search by name
     * @param string $name
     */
    public function searchByName(string $name)
    {
        $condition = Criteria::expr()->eq('name', $name);
        $this->criteria->andWhere($condition);
    }

    /**
     * provide search by city
     * @param string $city
     */
    public function searchByCity(string $city)
    {
        $condition = Criteria::expr()->eq('city', $city);
        $this->criteria->andWhere($condition);
    }

    /**
     * provide search by price range
     * @param float $from
     * @param float $to
     */
    public function searchByPrice(float $from, float $to)
    {
        $priceFromCondition = Criteria::expr()->gte('price', $from);
        $priceToCondition = Criteria::expr()->lte('price', $to);
        $this->criteria->andWhere($priceFromCondition)->andWhere($priceToCondition);
    }

    /**
     * provide sort by (name - price)
     * @param string $sort
     */
    public function sortBy(string $sort)
    {
        if(key_exists($sort, $this->sortBy)){
            $this->criteria->orderBy([ $sort => $this->sortBy[$sort] ]);
        }
    }
}
