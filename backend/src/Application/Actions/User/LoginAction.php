<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Services\JwtServiceInterface;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpUnauthorizedException;

class LoginAction extends Action
{
    protected ContainerInterface $c;
    private UserRepository $userRepository;
    private JwtServiceInterface $jwtService;

    public function __construct(
        UserRepository $userRepository,
        JwtServiceInterface $jwtService,
        LoggerInterface $logger,
        ContainerInterface $c
    ) {
        parent::__construct($logger);
        $this->c = $c;
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
        $username = $data['username'];
        $password = $data['password'];
        $user = $this->userRepository->findByUsernameOrEmailAndPassword($username, $password);
        if (!$user) {
            throw new HttpUnauthorizedException($this->request, 'Invalid credentials');
        }
        $jwt = $this->createJwtToken($user);
        $data = [
            'user' => $user,
            'token' => $jwt
        ];
        return $this->respondWithData($data);
    }
}