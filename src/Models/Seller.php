<?php

namespace App\Models;

class Seller
{
    public function __construct(
        private int $id,
        private string $idTelegram,
        private string|null $username,
        private string $idChat,
        private string|null $isModerate,
    )
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function idTelegram(): string
    {
        return $this->idTelegram;
    }

    public function username(): string|null
    {
        return $this->username;
    }

    public function idChat(): string
    {
        return $this->idChat;
    }

    public function isModerate(): string
    {
        return $this->isModerate;
    }
}