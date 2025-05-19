<?php

declare(strict_types=1);

use App\services\Weather\Application\QueryHandler\WeatherQueryHandler;
use App\services\Weather\Application\QueryResult\WeatherQueryResult;
use App\services\Weather\Domain\DTO\WeatherDTO;
use App\services\Weather\Domain\Exception\WeatherNotFoundException;
use App\services\Weather\Domain\Query\Weather\WeatherQuery;
use App\services\Weather\Domain\Repository\WeatherRepositoryInterface;
use App\services\Weather\Domain\ValueObject\Weather\City;
use PHPUnit\Framework\TestCase;

class WeatherQueryHandlerTest extends TestCase
{
    public function testHandleReturnsWeatherQueryResultWhenWeatherIsFound()
    {
        $city = new City('Paris');

        $weatherData = new WeatherDTO(
            1.0,
            1,
            'sdsd',
        );

        $repositoryMock = $this->createMock(WeatherRepositoryInterface::class);
        $repositoryMock->expects($this->once())
                       ->method('getForCity')
                       ->with($city)
                       ->willReturn($weatherData);

        $query = $this->createMock(WeatherQuery::class);
        $query->expects($this->once())
              ->method('getCity')
              ->willReturn($city);

        $handler = new WeatherQueryHandler($repositoryMock);
        $result = $handler->handle($query);

        $this->assertInstanceOf(WeatherQueryResult::class, $result);
    }

    public function testHandleThrowsWeatherNotFoundExceptionWhenWeatherIsNull()
    {
        $city = new City('dsdsd');

        $repositoryMock = $this->createMock(WeatherRepositoryInterface::class);
        $repositoryMock->expects($this->once())
                       ->method('getForCity')
                       ->with($city)
                       ->willReturn(null);

        $query = $this->createMock(WeatherQuery::class);
        $query->expects($this->once())
              ->method('getCity')
              ->willReturn($city);

        $handler = new WeatherQueryHandler($repositoryMock);

        $this->expectException(WeatherNotFoundException::class);
        $handler->handle($query);
    }
}