<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\ValueObject\Subscription;

class Token
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        $token = hash('sha256', bin2hex(random_bytes(32)) . time());
        return new self($token);
    }
}