<?php

declare(strict_types=1);

namespace App\services\Weather\Domain\Repository;

use App\services\Weather\Domain\DTO\WeatherDTO;
use App\services\Weather\Domain\ValueObject\Weather\City;

interface WeatherRepositoryInterface
{
    public function getForCity(City $city): ?WeatherDTO;
}