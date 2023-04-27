<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\Event\AccountRegistered;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserRepository;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class RegisterAction extends Action
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

        $username = $data['username'] ?? null;
        $firstName = $data['firstName'] ?? null;
        $lastName = $data['lastName'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $confirmPassword = $data['confirmPassword'] ?? null;

        $validator = v::key('username', v::stringType()->notEmpty()->setName('Username'))
            ->key('firstName', v::stringType()->notEmpty()->setName('First Name'))
            ->key('lastName', v::stringType()->notEmpty()->setName('Last Name'))
            ->key('email', v::email()->setName('Email'))
            ->key('password', v::stringType()->notEmpty()->setName('Password'))
            ->key('confirmPassword', v::equals($password)->setName('Confirm Password')->setTemplate('Confirm password must match password'));

        try {
            $validator->assert($data);
        } catch (NestedValidationException $exception) {
            $errors = $exception->getMessages();
            return $this->respondWithData(['errors' => $errors], 422);
        }

        $userData = [
            'username' => $username,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $password,
            'confirmPassword' => $confirmPassword,
        ];

        try {
            $user = $this->userRepository->createUser($userData);
            $event = new AccountRegistered($user->getId());
            $this->dispatcher->dispatch($event);
        } catch (UserAlreadyExistsException $e) {
            return $this->respondWithData(['error' => 'Username or email is already taken'], 409);
        } catch (Exception $e) {
            return $this->respondWithData(['error' => 'Error registering. Please contact support.'], 500);
        }

        return $this->respondWithData([]);
    }
}