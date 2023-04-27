<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ActivateAccountAction extends Action
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $activationCode = $data['activationCode'];

        try {
            $user = $this->userRepository->getUserByToken($activationCode);
            $this->userRepository->activateAccount($user->getId());
        } catch (Exception $e) {
            return $this->respondWithData(['error' => 'Token not found.'], 404);
        }

        return $this->respondWithData([]);
    }
}