<?php

namespace App\Application\Services;

use Firebase\JWT\JWT;

class JwtService implements JwtServiceInterface
{
    private $secret;
    private $alg;

    public function __construct($secret, $alg)
    {
        $this->secret = $secret;
        $this->alg = $alg;
    }

    public function createToken(array $payload): string
    {
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = $issuedAt +  (3 * 60 * 60);

        $tokenPayload = array_merge($payload, [
            "iat" => $issuedAt,
            "nbf" => $notBefore,
            "exp" => $expire,
        ]);

        return JWT::encode($tokenPayload, $this->secret, $this->alg);
    }

    public function verifyToken(string $token): bool
    {
        try {
            JWT::decode($token, $this->secret, [$this->alg]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function decodeToken(string $token): array
    {
        return (array) JWT::decode($token, $this->secret, [$this->alg]);
    }
}
