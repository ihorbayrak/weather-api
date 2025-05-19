<?php

declare(strict_types=1);

namespace App\services\Subscription\Domain\Repository;

use App\services\Subscription\Domain\Entity\Subscription\Subscription;
use App\services\Subscription\Domain\ValueObject\Subscription\Email;
use App\services\Subscription\Domain\ValueObject\Subscription\Token;

interface SubscriptionRepositoryInterface
{
    public function findByEmail(Email $email): ?Subscription;

    public function findByToken(Token $token): ?Subscription;

    public function save(Subscription $subscription): void;

    public function delete(Subscription $subscription): void;
}