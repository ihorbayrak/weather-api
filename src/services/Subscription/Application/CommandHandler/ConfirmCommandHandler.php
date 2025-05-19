<?php

declare(strict_types=1);

namespace App\services\Subscription\Application\CommandHandler;

use App\services\Subscription\Domain\Command\ConfirmCommand;
use App\services\Subscription\Domain\Exception\SubscriptionAlreadyConfirmedException;
use App\services\Subscription\Domain\Exception\SubscriptionNotFoundException;
use App\services\Subscription\Domain\Repository\SubscriptionRepositoryInterface;

class ConfirmCommandHandler
{
    public function __construct(private SubscriptionRepositoryInterface $subscriptionRepository) {}

    public function handle(ConfirmCommand $command): void
    {
        $subscription = $this->subscriptionRepository->findByToken($command->token);

        if (!$subscription) {
            throw new SubscriptionNotFoundException();
        }

        if ($subscription->isConfirmed()) {
            throw new SubscriptionAlreadyConfirmedException();
        }

        $subscription->confirm();

        $this->subscriptionRepository->save($subscription);
    }
}