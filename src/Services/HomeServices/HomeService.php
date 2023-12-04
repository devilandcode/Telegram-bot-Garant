<?php

namespace App\Services\HomeServices;

use App\Keyboards\Keyboards;
use App\Messages\Messages;

class HomeService
{
    public function __construct(
        private Messages $botAnswer,
        private Keyboards $botKeyboards,
    )
    {
    }

    public function sendStartMenu()
    {
        $this->botKeyboards->start();
    }
}