<?php

declare(strict_types=1);

namespace App\services\Weather\Presentation\Response;

use App\services\Weather\Domain\DTO\WeatherDTO;

class WeatherResponse
{
    private WeatherDTO $weather;

    public function __construct(WeatherDTO $weather)
    {
        $this->weather = $weather;
    }

    public function data(): array
    {
        return [
            'temperature' => $this->weather->temperature,
            'humidity' => $this->weather->humidity,
            'description' => $this->weather->description,
        ];
    }
}