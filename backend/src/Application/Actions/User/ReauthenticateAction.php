<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Services\JwtServiceInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class ReauthenticateAction extends Action
{
    private UserRepository $userRepository;
    private JwtServiceInterface $jwtService;

    public function __construct(
        UserRepository $userRepository,
        JwtServiceInterface $jwtService,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
        $this->logger = $logger;
    }

    protected function createJwtToken(User $user): string
    {
        $tokenPayload = [
            "sub" => $user->getId(),
            "name" => $user->getUsername(),
            "email" => $user->getEmail(),
        ];
        return $this->jwtService->createToken($tokenPayload);
    }
    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $token = $data['token'];

        $decoded = $this->jwtService->decodeToken($token);
        $userEmail = $decoded['email'];
        $user = $this->userRepository->findUserByEmail($userEmail);

        $jwt = $this->createJwtToken($user);
        $data = [
            'user' => $user,
            'token' => $jwt
        ];

        return $this->respondWithData($data);
    }
}