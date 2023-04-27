<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    private ?int $id;
    private string $username;
    private string $firstName;
    private string $lastName;
    private string $email;
    private ?string $token;
    private ?string $forgetToken;

    public function __construct(array $userData) {
        $this->id = $userData['id'] ? intval($userData['id']) :  null;
        $this->username = strtolower($userData['username']);
        $this->firstName = ucfirst($userData['first_name']);
        $this->lastName = ucfirst($userData['last_name']);
        $this->email = $userData['email'];
        $this->token = $userData['token'] ?? null;
        $this->forgetToken = $userData['forgot_token'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'token' => $this->token,
            'forgetToken' => $this->forgetToken,
        ];
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getForgetToken()
    {
        return $this->forgetToken;
    }

    public function setForgetToken(string $token): void
    {
        $this->forgetToken = $token;
    }
}
