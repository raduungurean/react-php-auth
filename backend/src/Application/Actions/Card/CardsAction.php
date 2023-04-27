<?php

namespace App\Application\Actions\Card;

use App\Application\Actions\Action;
use App\Domain\Card\CardRepository;
use Psr\Log\LoggerInterface;

abstract class CardsAction extends Action
{
    protected CardRepository $cardRepository;

    public function __construct(LoggerInterface $logger, CardRepository $cardRepository)
    {
        parent::__construct($logger);
        $this->cardRepository = $cardRepository;
    }
}