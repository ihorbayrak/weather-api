<?php

declare(strict_types=1);

namespace App\services\Weather\Presentation\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GetWeatherRequest
{
    public function __construct(public readonly string $city) {}

    public static function fromHttpRequest(Request $request): self
    {
        $city = $request->query->get('city');

        if (!$city || !is_string($city)) {
            throw new BadRequestHttpException('City parameter is required.');
        }

        return new self($city);
    }
}