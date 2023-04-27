<?php

namespace App\Application\Actions\Card;

use Psr\Http\Message\ResponseInterface as Response;

class ListCardsAction extends CardsAction
{
    protected function action(): Response
    {
        $cards = $this->cardRepository->findAll();

        return $this->respondWithData($cards);
    }
}