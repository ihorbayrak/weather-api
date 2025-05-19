<?php

declare(strict_types=1);

namespace App\services\Subscription\Presentation\Controller;

use App\services\Subscription\Application\CommandHandler\ConfirmCommandHandler;
use App\services\Subscription\Application\CommandHandler\SubscribeCommandHandler;
use App\services\Subscription\Application\CommandHandler\UnsubscribeCommandHandler;
use App\services\Subscription\Domain\Command\ConfirmCommand;
use App\services\Subscription\Domain\Command\SubscribeCommand;
use App\services\Subscription\Domain\Command\UnsubscribeCommand;
use App\services\Subscription\Domain\Exception\EmailAlreadySubscribedException;
use App\services\Subscription\Domain\Exception\SubscriptionAlreadyConfirmedException;
use App\services\Subscription\Domain\Exception\SubscriptionNotFoundException;
use App\services\Subscription\Domain\ValueObject\Subscription\Email;
use App\services\Subscription\Domain\ValueObject\Subscription\Frequency;
use App\services\Subscription\Domain\ValueObject\Subscription\Token;
use App\services\Subscription\Presentation\Request\SubscribeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SubscriptionController extends AbstractController
{
    public function __construct(
        private readonly SubscribeCommandHandler $subscribeCommandHandler,
        private readonly UnsubscribeCommandHandler $unsubscribeCommandHandler,
        private readonly ConfirmCommandHandler $confirmCommandHandler,
    ) {}

    #[Route('/api/subscribe', name: 'api_subscribe', methods: ['POST'])]
    public function subscribe(Request $request): JsonResponse
    {
        $subscribeRequest = SubscribeRequest::fromHttpRequest($request);

        try {
            $command = new SubscribeCommand(
                new Email($subscribeRequest->email),
                $subscribeRequest->city,
                Frequency::from($subscribeRequest->frequency)
            );

            $this->subscribeCommandHandler->handle($command);
        } catch (EmailAlreadySubscribedException $exception) {
            return new JsonResponse(['message' => 'Email already subscribed'], 409);
        }

        return new JsonResponse(['message' => 'Subscription successful. Confirmation email sent.']);
    }

    #[Route('/api/confirm/{token}', name: 'api_confirm', methods: ['GET'])]
    public function confirm(string $token): JsonResponse
    {
        try {
            $command = new ConfirmCommand(new Token($token));

            $this->confirmCommandHandler->handle($command);
        } catch (SubscriptionAlreadyConfirmedException) {
            return new JsonResponse(['message' => 'Already confirmed']);
        } catch (SubscriptionNotFoundException) {
            return new JsonResponse(['message' => 'Token not found'], 404);
        }

        return new JsonResponse(['message' => 'Subscription confirmed successfully']);
    }

    #[Route('/api/unsubscribe/{token}', name: 'api_unsubscribe', methods: ['GET'])]
    public function unsubscribe(string $token): JsonResponse
    {
        try {
            $command = new UnsubscribeCommand(new Token($token));

            $this->unsubscribeCommandHandler->handle($command);
        } catch (SubscriptionNotFoundException $exception) {
            return new JsonResponse(['message' => 'Token not found'], 404);
        }

        return new JsonResponse(['message' => 'Unsubscribed successfully']);
    }
}