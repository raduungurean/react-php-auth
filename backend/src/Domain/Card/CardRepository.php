<?php

declare(strict_types=1);

namespace App\Domain\Card;

interface CardRepository
{
    public function findAll(): array;
    public function findCardOfId(int $id): Card;
}
