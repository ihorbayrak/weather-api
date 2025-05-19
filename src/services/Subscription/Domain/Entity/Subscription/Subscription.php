<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Entity\Subscription;

use App\services\Subscription\Domain\ValueObject\Subscription\Email;
use App\services\Subscription\Domain\ValueObject\Subscription\Frequency;
use App\services\Subscription\Domain\ValueObject\Subscription\Token;

class Subscription
{
    private ?int $id;
    private Email $email;
    private string $city;
    private Frequency $frequency;
    private Token $token;
    private bool $confirmed;

    public function __construct(
        ?int $id,
        Email $email,
        string $city,
        Frequency $frequency,
        Token $token,
        bool $confirmed,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->city = $city;
        $this->frequency = $frequency;
        $this->token = $token;
        $this->confirmed = $confirmed;
    }

    public function confirm(): void
    {
        $this->confirmed = true;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getFrequency(): Frequency
    {
        return $this->frequency;
    }
}