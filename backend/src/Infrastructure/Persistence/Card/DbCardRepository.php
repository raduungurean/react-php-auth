<?php

namespace App\Infrastructure\Persistence\Card;

use App\Domain\Card\Card;
use App\Domain\Card\CardRepository;
use DateTime;
use Exception;
use PDO;

class DbCardRepository implements CardRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM cards');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cards = [];

        foreach ($data as $row) {
            $card = new Card(
                $row['id'],
                $row['title'],
                $row['description'],
                new DateTime($row['created_at']),
                new DateTime($row['updated_at'])
            );
            $cards[] = $card;
        }

        return $cards;
    }

    public function findCardOfId(int $id): Card
    {
        $stmt = $this->db->prepare('SELECT * FROM cards WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception('Card not found');
        }

        $card = new Card(
            $row['id'],
            $row['title'],
            $row['description'],
            new DateTime($row['created_at']),
            new DateTime($row['updated_at'])
        );

        return $card;
    }
}
