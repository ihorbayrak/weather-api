<?php

declare(strict_types=1);

namespace App\services\Weather\Application\QueryHandler;

use App\services\Weather\Application\QueryResult\WeatherQueryResult;
use App\services\Weather\Domain\Exception\WeatherNotFoundException;
use App\services\Weather\Domain\Query\Weather\WeatherQuery;
use App\services\Weather\Domain\Repository\WeatherRepositoryInterface;

class WeatherQueryHandler
{
    public function __construct(private readonly WeatherRepositoryInterface $repository) {}

    public function handle(WeatherQuery $query): WeatherQueryResult
    {
        $weather = $this->repository->getForCity($query->getCity());

        if ($weather === null) {
            throw new WeatherNotFoundException();
        }

        return new WeatherQueryResult($weather);
    }
}