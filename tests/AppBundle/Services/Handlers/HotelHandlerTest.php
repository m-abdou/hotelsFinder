<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\Hotel;

class HotelHandlerTest extends WebTestCase
{

    public function setUp()
    {
        static::bootKernel();
        $kernel = static::$kernel;
        $this->hotelHandlerService = $kernel->getContainer()->get('service.handler.hotel');
    }

    public function testSearchByName()
    {
        $this->mockObjects();
        $hotels = $this->hotelHandlerService->searchByQuery(['name' => 'meridian']);
        $this->assertEquals($hotels['hotels'][0]['name'], 'meridian');
    }

    public function testSearchByPrice()
    {
        $this->mockObjects();
        $hotels = $this->hotelHandlerService->searchByQuery(['price_from' => 40, 'price_to' => 60]);
        $this->assertEquals($hotels['hotels'][0]['name'], 'Wing');
    }

    public function testSortBy()
    {
        $this->mockObjects();
        $hotels = $this->hotelHandlerService->searchByQuery(['sort_by' => 'price']);
        $this->assertEquals($hotels['hotels'][0]['name'], 'Wing');
    }

    private function mockObjects()
    {
        $hotels = new ArrayCollection();
        $hotel = new Hotel();
        $hotel->setName('meridian');
        $hotel->setPrice(20);
        $hotel->setCity('cairo');
        $hotel->setAvailability([
            [
                'from' => '10-10-2020',
                'to' => '15-10-2020'
            ],
            [
                'from' => '25-10-2020',
                'to' => '15-11-2020'
            ],[
                'from' => '10-12-2020',
                'to' => '15-12-2020'
            ],
        ]);
        $hotels->add($hotel);
        $hotel = new Hotel();
        $hotel->setName('Wing');
        $hotel->setPrice(50);
        $hotel->setCity('Alexandria');
        $hotel->setAvailability([
            [
                'from' => '10-10-2020',
                'to' => '15-10-2020'
            ],
            [
                'from' => '25-10-2020',
                'to' => '15-11-2020'
            ],[
                'from' => '10-12-2020',
                'to' => '15-12-2020'
            ],
        ]);
        $hotels->add($hotel);

        $this->hotelHandlerService->setHotels($hotels);
    }
}