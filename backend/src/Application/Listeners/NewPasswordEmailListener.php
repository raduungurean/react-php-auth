<?php

namespace App\Application\Listeners;

use App\Domain\Event\NewPasswordCreated;
use App\Domain\User\UserRepository;
use App\Infrastructure\Mailer\Mailer;
use Exception;
use Psr\Http\Client\ClientExceptionInterface;

class NewPasswordEmailListener
{
    private Mailer $mailer;
    private UserRepository $userRepository;
    private string $appUrl;

    public function __construct(string $appUrl, $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->appUrl = $appUrl;
    }

    /**
     * @throws Exception
     * @throws ClientExceptionInterface
     */
    public function __invoke(NewPasswordCreated $event): void
    {
        $user = $this->userRepository->findUserOfId($event->getUserId());
        $this->mailer->sendNewPasswordCreatedEmail($user, $this->appUrl . '/new-password/' . $user->getForgetToken());
    }
}