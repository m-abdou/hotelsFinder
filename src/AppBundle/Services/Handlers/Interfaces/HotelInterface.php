<?php

namespace AppBundle\Services\Handlers\Interfaces;


interface HotelInterface
{
    public function searchByAvailability(string $availableFrom, string $availableTo);

    public function searchByName(string $name);

    public function searchByCity(string $city);

    public function searchByPrice(float $from, float $to);

    public function sortBy(string $sortFild);
}