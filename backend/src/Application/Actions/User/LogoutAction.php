<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class LogoutAction extends Action
{
    public function __construct(
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->logger = $logger;
    }

    protected function action(): Response
    {
        $data = [
            'success' => true,
        ];
        return $this->respondWithData($data);
    }
}