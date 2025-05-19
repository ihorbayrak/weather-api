<?php

declare(strict_types=1);

namespace App\services\Subscription\Infrastructure\Repository;

use App\services\Subscription\Domain\Entity\Subscription\Subscription;
use App\services\Subscription\Domain\Factory\Subscription\SubscriptionFactory;
use App\services\Subscription\Domain\Repository\SubscriptionRepositoryInterface;
use App\services\Subscription\Domain\ValueObject\Subscription\Email;
use App\services\Subscription\Domain\ValueObject\Subscription\Frequency;
use App\services\Subscription\Domain\ValueObject\Subscription\Token;
use Doctrine\DBAL\Connection;
use PDO;

class RawSubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function __construct(private Connection $connection, private SubscriptionFactory $subscriptionFactory) {}

    public function findByEmail(Email $email): ?Subscription
    {
        $sql = 'SELECT * FROM subscription WHERE email = :email LIMIT 1';
        $result = $this->connection->fetchAssociative($sql, ['email' => $email->getValue()]);

        return $result ? $this->hydrateSubscription($result) : null;
    }

    public function findByToken(Token $token): ?Subscription
    {
        $sql = 'SELECT * FROM subscription WHERE token = :token LIMIT 1';
        $result = $this->connection->fetchAssociative($sql, ['token' => $token->getValue()]);

        return $result ? $this->hydrateSubscription($result) : null;
    }

    public function save(Subscription $subscription): void
    {
        $sql = 'INSERT INTO subscription (email, city, frequency, token, confirmed) 
                VALUES (:email, :city, :frequency, :token, :confirmed)
                ON CONFLICT (email) DO UPDATE SET confirmed = EXCLUDED.confirmed';

        $this->connection->executeStatement($sql, [
            'email' => $subscription->getEmail()->getValue(),
            'token' => $subscription->getToken()->getValue(),
            'city' => $subscription->getCity(),
            'frequency' => $subscription->getFrequency()->value,
            'confirmed' => $subscription->isConfirmed(),
        ], [
            'confirmed' => PDO::PARAM_BOOL
        ]);
    }

    public function delete(Subscription $subscription): void
    {
        $sql = 'DELETE FROM subscription WHERE email = :email';
        $this->connection->executeStatement($sql, [
            'email' => $subscription->getEmail()->getValue(),
        ]);
    }

    private function hydrateSubscription(array $data): Subscription
    {
        return $this->subscriptionFactory->make(
            $data['id'],
            new Email($data['email']),
            $data['city'],
            Frequency::from($data['frequency']),
            new Token($data['token']),
            $data['confirmed']
        );
    }
}