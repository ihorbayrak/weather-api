<?php

declare(strict_types=1);

namespace App\services\Weather\Infrastructure\Api\Provider;

use App\services\Weather\Domain\DTO\WeatherDTO;
use App\services\Weather\Domain\ValueObject\Weather\City;

interface WeatherProviderInterface
{
    public function getWeatherForCity(City $city): ?WeatherDTO;
}