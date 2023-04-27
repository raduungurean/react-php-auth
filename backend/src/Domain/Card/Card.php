<?php

declare(strict_types=1);

namespace App\Domain\Card;

use JsonSerializable;

class Card implements JsonSerializable
{
    private ?int $id;
    private string $title;
    private string $description;
    private $created_at;
    private $updated_at;

    public function __construct(?int $id, string $title, string $description, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->title = strtolower($title);
        $this->description = $description;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
