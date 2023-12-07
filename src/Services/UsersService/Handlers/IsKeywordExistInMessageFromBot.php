<?php

namespace App\Services\UsersService\Handlers;

class isKeywordExistInMessageFromBot
{
    public function check(string $messageFromBot): bool
    {
        $amountKeywords = require_once APP_PATH .'/config/amountKeywords.php';

        foreach ($amountKeywords as $word) {
            if (str_contains($messageFromBot, $word)) {
                return true;
            }
        }

        return false;
    }

}