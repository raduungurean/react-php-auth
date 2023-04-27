<?php

namespace App\Application\Services;

interface JwtServiceInterface
{
    public function createToken(array $payload): string;
    public function verifyToken(string $token): bool;
    public function decodeToken(string $token): array;
}