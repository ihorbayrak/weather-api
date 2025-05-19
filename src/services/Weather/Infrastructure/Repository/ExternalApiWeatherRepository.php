<?php

declare(strict_types=1);

namespace App\services\Weather\Infrastructure\Repository;

use App\services\Weather\Domain\DTO\WeatherDTO;
use App\services\Weather\Domain\Repository\WeatherRepositoryInterface;
use App\services\Weather\Domain\ValueObject\Weather\City;
use App\services\Weather\Infrastructure\Api\Provider\WeatherProviderInterface;

class ExternalApiWeatherRepository implements WeatherRepositoryInterface
{
    private WeatherProviderInterface $weatherProvider;

    public function __construct(WeatherProviderInterface $weatherProvider)
    {
        $this->weatherProvider = $weatherProvider;
    }

    public function getForCity(City $city): ?WeatherDTO
    {
        return $this->weatherProvider->getWeatherForCity($city);
    }
}