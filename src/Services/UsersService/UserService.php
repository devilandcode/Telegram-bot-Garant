<?php

namespace App\Services\UsersService;

class UserService
{
    public function isTelegramId(string $messageFromBot)
    {
        if (is_numeric($messageFromBot) && strlen($messageFromBot) === 10) {
            return true;
        }
        return false;
    }


}