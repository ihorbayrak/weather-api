<?php

declare(strict_types=1);

namespace App\services\Weather\Application\QueryResult;

use App\services\Weather\Domain\DTO\WeatherDTO;

class WeatherQueryResult
{
    public function __construct(public readonly WeatherDTO $weather)
    {
    }
}