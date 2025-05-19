<?php

declare(strict_types=1);

namespace App\services\Weather\Domain\Query\Weather;

use App\services\Weather\Domain\ValueObject\Weather\City;

class WeatherQuery
{
    private City $city;

    public function __construct(City $city)
    {
        $this->city = $city;
    }

    public function getCity(): City
    {
        return $this->city;
    }
}