<?php

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tuupola\Middleware\JwtAuthentication;

class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private string $secret;

    /**
     * @var string
     */
    private string $algorithm;

    /**
     * JwtAuthMiddleware constructor.
     * @param string $secret
     * @param string $algorithm
     */
    public function __construct(string $secret, string $algorithm)
    {
        $this->secret = $secret;
        $this->algorithm = $algorithm;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $jwtMiddleware = new JwtAuthentication([
            'secure' => false, // Should be set to true in production
            'relaxed' => ['localhost'], // Add any other domains that are allowed to access the API
            'secret' => $this->secret,
            'algorithm' => [$this->algorithm],
            'path' => ['/api'], // Set the path where the JWT token is required
            'error' => function (ResponseInterface $response, array $arguments) {
                $data = [
                    'status' => 'error',
                    'message' => $arguments['message'],
                ];
                $response = $response->withHeader('Content-Type', 'application/json');
                $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                return $response;
            },
        ]);

        return $jwtMiddleware->process($request, $handler);
    }
}
