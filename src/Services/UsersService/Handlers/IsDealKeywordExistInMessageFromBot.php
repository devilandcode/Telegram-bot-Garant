<?php

namespace App\Services\UsersService\Handlers;

class IsDealKeywordExistInMessageFromBot
{
    public function check(string $messageFromBot): bool
    {
        $amountKeywords = require_once APP_PATH .'/config/dealKeywords.php';

        foreach ($amountKeywords as $word) {
            if (str_contains($messageFromBot, $word)) {
                return true;
            }
        }

        return false;
    }
}