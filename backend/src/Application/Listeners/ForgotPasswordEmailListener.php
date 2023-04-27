<?php

namespace App\Application\Listeners;

use App\Domain\Event\NewPasswordRequested;
use App\Domain\User\UserRepository;
use App\Infrastructure\Mailer\Mailer;
use Exception;

class ForgotPasswordEmailListener
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
    public function __invoke(NewPasswordRequested $event): void
    {
        $user = $this->userRepository->findUserOfId($event->getUserId());
        $this->mailer->sendForgetPasswordEmail($user, $this->appUrl . '/new-password/' . $user->getForgetToken());
    }
}