<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\Event\NewPasswordCreated;
use App\Domain\User\UserRepository;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class NewPasswordAction extends Action
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
        $confirmPassword = $data['confirmPassword'] ?? null;
        $token = $data['token'] ?? null;
        $password = $data['password'] ?? null;

        $validator = v::key('password', v::stringType()->notEmpty()->setName('Password'))
            ->key('token', v::stringType()->notEmpty()->setName('User token'))
            ->key('confirmPassword', v::equals($password)->setName('Confirm Password')->setTemplate('Confirm password must match password'));

        try {
            $validator->assert($data);
        } catch (NestedValidationException $exception) {
            $errors = $exception->getMessages();
            return $this->respondWithData(['errors' => $errors], 422);
        }

        try {
            $user = $this->userRepository->getUserByForgotToken($token);
            $this->userRepository->updatePassword($user->getId(), $password);
            $event = new NewPasswordCreated($user->getId());
            $this->dispatcher->dispatch($event);
        } catch (Exception $e) {
            return $this->respondWithData(['error' => 'Error creating password. Please contact support.' . $e->getMessage()], 500);
        }

        return $this->respondWithData([]);
    }
}