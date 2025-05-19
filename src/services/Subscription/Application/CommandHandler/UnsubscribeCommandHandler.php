<?php

declare(strict_types=1);

namespace App\services\Subscription\Application\CommandHandler;

use App\services\Subscription\Domain\Command\UnsubscribeCommand;
use App\services\Subscription\Domain\Exception\SubscriptionNotFoundException;
use App\services\Subscription\Domain\Repository\SubscriptionRepositoryInterface;

class UnsubscribeCommandHandler
{
    public function __construct(private SubscriptionRepositoryInterface $subscriptionRepository) {}

    public function handle(UnsubscribeCommand $command): void
    {
        $subscription = $this->subscriptionRepository->findByToken($command->token);

        if (!$subscription) {
            throw new SubscriptionNotFoundException();
        }

        $this->subscriptionRepository->delete($subscription);
    }
}