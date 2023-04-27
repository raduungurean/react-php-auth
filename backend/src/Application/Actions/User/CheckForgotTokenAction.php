<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CheckForgotTokenAction extends Action
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
        $token = $data['token'];

        try {
            $this->userRepository->getUserByForgotToken($token);
        } catch (UserNotFoundException $e) {
            return $this->respondWithData(['error' => 'Token not found.'], 404);
        }

        return $this->respondWithData(['token' => $token]);
    }
}