<?php

declare(strict_types=1);

namespace App\services\Weather\Presentation\Controller;

use App\services\Weather\Application\QueryHandler\WeatherQueryHandler;
use App\services\Weather\Domain\Exception\WeatherNotFoundException;
use App\services\Weather\Domain\Query\Weather\WeatherQuery;
use App\services\Weather\Domain\ValueObject\Weather\City;
use App\services\Weather\Presentation\Request\GetWeatherRequest;
use App\services\Weather\Presentation\Response\WeatherResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

final class WeatherController extends AbstractController
{
    public function __construct(private readonly WeatherQueryHandler $handler) {}

    #[Route('/api/weather', name: 'get_weather', methods: ['GET'])]
    public function getWeather(Request $request): JsonResponse
    {
        $getWeatherRequest = GetWeatherRequest::fromHttpRequest($request);

        try {
            $city = new City($getWeatherRequest->city);
            $query = new WeatherQuery($city);

            $queryResult = $this->handler->handle($query);

            return $this->json(
                (new WeatherResponse($queryResult->weather))->data()
            );
        } catch (WeatherNotFoundException $exception) {
            throw new NotFoundHttpException('City not found.');
        } catch (Throwable $exception) {
            return $this->json([]);
        }
    }
}