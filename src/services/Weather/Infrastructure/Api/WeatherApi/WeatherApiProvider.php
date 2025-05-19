<?php

declare(strict_types=1);

namespace App\services\Weather\Infrastructure\Api\WeatherApi;

use App\services\Weather\Domain\DTO\WeatherDTO;
use App\services\Weather\Domain\ValueObject\Weather\City;
use App\services\Weather\Infrastructure\Api\Provider\WeatherProviderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class WeatherApiProvider implements WeatherProviderInterface
{
    public function __construct(private readonly HttpClientInterface $client) {}

    public function getWeatherForCity(City $city): ?WeatherDTO
    {
        try {
            $apiKey = $_ENV['WEATHER_API_API_KEY'];
            $response = $this->client->request('GET', $_ENV['WEATHER_API_API_URL'], [
                'query' => [
                    'key' => $apiKey,
                    'q' => $city->getValue(),
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $data = $response->toArray();

            return new WeatherDTO(
                $data['current']['temp_c'],
                $data['current']['humidity'],
                $data['current']['condition']['text']
            );
        } catch (Throwable $exception) {
            return null;
        }
    }
}