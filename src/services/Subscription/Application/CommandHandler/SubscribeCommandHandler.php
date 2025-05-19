<?php

declare(strict_types=1);

namespace App\services\Subscription\Application\CommandHandler;

use App\services\Subscription\Domain\Command\SubscribeCommand;
use App\services\Subscription\Domain\Event\SubscriptionCreatedEvent;
use App\services\Subscription\Domain\Exception\EmailAlreadySubscribedException;
use App\services\Subscription\Domain\Factory\Subscription\SubscriptionFactory;
use App\services\Subscription\Domain\Repository\SubscriptionRepositoryInterface;
use App\services\Subscription\Domain\ValueObject\Subscription\Token;
use App\services\Subscription\Infrastructure\Queue\SubscriptionQueue;

class SubscribeCommandHandler
{
    private SubscriptionRepositoryInterface $subscriptionRepository;
    private SubscriptionFactory $subscriptionFactory;

    public function __construct(SubscriptionRepositoryInterface $repository, SubscriptionFactory $subscriptionFactory)
    {
        $this->subscriptionRepository = $repository;
        $this->subscriptionFactory = $subscriptionFactory;
    }

    public function handle(SubscribeCommand $command): void
    {
        if ($this->subscriptionRepository->findByEmail($command->email)) {
            throw new EmailAlreadySubscribedException();
        }

        $token = Token::generate();
        $subscription = $this->subscriptionFactory->makeNew(
            $command->email,
            $command->city,
            $command->frequency,
            $token,
        );

        $this->subscriptionRepository->save($subscription);

        SubscriptionQueue::subscribe(
            new SubscriptionCreatedEvent(
                $subscription->getEmail()->getValue(),
                $subscription->getToken()->getValue()
            )
        );
    }
}