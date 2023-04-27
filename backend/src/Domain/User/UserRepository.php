<?php

declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;
    public function findUserByEmail(string $email): User;
    /**
     * @throws UserAlreadyExistsException
     */
    public function createUser(array $userData): User;
    public function findByUsernameOrEmailAndPassword(string $usernameOrEmail, string $password): ?User;
    public function activateAccount(int $uid);
    public function requestNewPassword(string $email);
    /**
     * @throws UserNotFoundException
     */
    public function getUserByForgotToken(string $token);
    public function updatePassword(int $id, string $password);
}
