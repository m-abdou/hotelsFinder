<?php

namespace AppBundle\Services\Handlers\Interfaces;

use \DateTime;

interface HotelInterface
{
    public function searchByAvailability(DateTime $availableFrom, DateTime $availableTo);

    public function searchByName(string $name);

    public function searchByCity(string $city);

    public function searchByPrice(float $from, float $to);

    public function sortBy(string $sortFild);
}