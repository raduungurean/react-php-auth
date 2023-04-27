<?php

namespace App\Application\Listeners;

use App\Domain\Event\AccountRegistered;
use App\Domain\User\UserRepository;
use App\Infrastructure\Mailer\Mailer;
use Exception;

class AccountActivatedEmailListener
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
     */
    public function __invoke(AccountRegistered $event): void
    {
        $user = $this->userRepository->findUserOfId($event->getUserId());
        $this->mailer->sendActivationEmail($user, $this->appUrl . '/activate/' . $user->getToken());
    }
}