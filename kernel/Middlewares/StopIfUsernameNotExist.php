<?php

namespace App\Kernel\Middlewares;

use App\Messages\Messages;

class StopIfUsernameNotExist implements MiddlewareInterface
{
    public function __construct(
        private Messages $botAnswer
    )
    {
    }

    public function check(array $stdClass): void
    {
        extract($stdClass);

        if ( ! isset($phpInput->message->chat->username)) {
            $this->botAnswer->askUserToSetUsername();
            die;
        }
    }
}