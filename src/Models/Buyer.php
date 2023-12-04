<?php

namespace App\Models;

class Buyer
{
    public function __construct(
        private int $id,
        private int $id_telegram,
        private string $username,
        private string $category,
        private string $image,
        private array $reviews = []
    )
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function image(): string
    {
        return $this->image;
    }

}