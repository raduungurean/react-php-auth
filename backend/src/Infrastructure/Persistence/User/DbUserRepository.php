<?php

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use Exception;
use PDO;

class DbUserRepository implements UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User(
                $row
            );
        }
        return $users;
    }

    public function findUserOfId(int $id): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new UserNotFoundException("User with id $id not found");
        }
        return new User(
            $user
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByEmail(string $email): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new UserNotFoundException("User with id $email not found");
        }
        return new User(
            $user
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function findByUsernameOrEmailAndPassword(string $usernameOrEmail, string $password): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE (username = :usernameOrEmail OR email = :usernameOrEmail) AND password = :password AND activated = 1');
        $stmt->execute([
            ':usernameOrEmail' => $usernameOrEmail,
            ':password' => md5($password),
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return new User(
            $user
        );
    }

    private function findByUsernameOrEmail(string $username, string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username OR email = :email');
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function createUser(array $userData): User
    {
        $username = $userData['username'];
        $email = $userData['email'];

        if ($this->findByUsernameOrEmail($username, $email)) {
            throw new UserAlreadyExistsException();
        }

        $stmt = $this->pdo->prepare('INSERT INTO users (username, first_name, last_name, email, password, token) VALUES (:username, :firstName, :lastName, :email, :password, :token)');
        $stmt->execute([
            ':username' => $userData['username'],
            ':firstName' => $userData['firstName'],
            ':lastName' => $userData['lastName'],
            ':email' => $userData['email'],
            ':password' => md5($userData['password']),
            ':token' => bin2hex(random_bytes(32)),
        ]);

        $userId = $this->pdo->lastInsertId();

        $userData['id'] = $userId;
        $userData['first_name'] = $userData['firstName'];
        $userData['last_name'] = $userData['lastName'];

        return new User(
            $userData
        );
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUserByToken(string $token): ?User
    {
        $query = "SELECT id, username, first_name, last_name, email, token, forgot_token FROM users WHERE token = :token LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new UserNotFoundException();
        }

        return new User(
            $row
        );
    }

    public function activateAccount(int $uid)
    {
        $query = "UPDATE users SET activated = 1, token = NULL WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $uid]);
    }

    /**
     * @throws Exception
     */
    public function requestNewPassword(string $email)
    {
        $user = $this->findUserByEmail($email);
        $forgotToken = bin2hex(random_bytes(32));

        $query = "UPDATE users SET forgot_token = :forgotToken WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['forgotToken' => $forgotToken, 'id' => $user->getId()]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUserByForgotToken(string $token)
    {
        $query = "SELECT id, username, first_name, last_name, email, token, forgot_token FROM users WHERE forgot_token = :forgot_token LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['forgot_token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new UserNotFoundException();
        }

        return new User(
            $row
        );
    }

    public function updatePassword(int $id, string $password)
    {
        $query = "UPDATE users SET activated = 1, password = :password, forgot_token = NULL WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id, 'password' => md5($password)]);
    }
}
