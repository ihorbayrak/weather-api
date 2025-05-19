<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Factory\Subscription;

use App\services\Subscription\Domain\Entity\Subscription\Subscription;
use App\services\Subscription\Domain\ValueObject\Subscription\Email;
use App\services\Subscription\Domain\ValueObject\Subscription\Frequency;
use App\services\Subscription\Domain\ValueObject\Subscription\Token;

class SubscriptionFactory
{
    public function make(
        ?int $id,
        Email $email,
        string $city,
        Frequency $frequency,
        Token $token,
        bool $confirmed
    ): Subscription {
        return new Subscription(
            id: $id,
            email: $email,
            city: $city,
            frequency: $frequency,
            token: $token,
            confirmed: $confirmed,
        );
    }

    public function makeNew(
        Email $email,
        string $city,
        Frequency $frequency,
        Token $token,
    ): Subscription {
        return $this->make(
            null,
            $email,
            $city,
            $frequency,
            $token,
            false
        );
    }
}