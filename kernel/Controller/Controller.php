<?php

namespace App\Kernel\Controller;

use App\Keyboards;

class Controller
{

    public function __construct(
        private Keyboards $botKeyboard,
    )
    {
    }

    private Keyboards $botKeyboards;

    public function start(): void
    {
        $this->botKeyboard->start();
    }

}