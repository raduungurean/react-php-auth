<?php

namespace App\Domain\User;

use Exception;
use Throwable;
class UserAlreadyExistsException extends Exception
{
    public function __construct(string $message = 'User already exists', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}