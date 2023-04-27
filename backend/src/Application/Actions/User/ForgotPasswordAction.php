<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\Event\NewPasswordRequested;
use App\Domain\User\UserRepository;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ForgotPasswordAction extends Action
{
    private UserRepository $userRepository;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        UserRepository  $userRepository,
        LoggerInterface $logger,
        EventDispatcherInterface $dispatcher
    ) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    protected function action(): Response
    {
        $data = $this->request->getParsedBody();
        $email = $data['email'] ?? null;

        $validator = v::key('email', v::email()->setName('Email'));

        try {
            $validator->assert($data);
        } catch (NestedValidationException $exception) {
            $errors = $exception->getMessages();
            return $this->respondWithData(['errors' => $errors], 422);
        }

        try {
            $this->userRepository->requestNewPassword($email);
            $user = $this->userRepository->findUserByEmail($email);
            $event = new NewPasswordRequested($user->getId());
            $this->dispatcher->dispatch($event);
        } catch (Exception $e) {
            return $this->respondWithData(['error' => 'Error recovering the password. Please contact support.'], 500);
        }

        return $this->respondWithData([]);
    }
}