<?php

declare(strict_types=1);

namespace App\services\Subscription\Presentation\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubscribeRequest
{
    public function __construct(
        public readonly string $city,
        public readonly string $email,
        public readonly string $frequency,
    ) {}

    public static function fromHttpRequest(Request $request): self
    {
        $email = $request->get('email');
        $city = $request->get('city');
        $frequency = $request->get('frequency');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$city || !in_array($frequency, ['hourly', 'daily'])) {
            throw new BadRequestHttpException('Invalid input');
        }

        return new self($city, $email, $frequency);
    }
}