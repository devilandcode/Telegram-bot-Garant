<?php

namespace App\Services\UsersService\Handlers;

class GetCryptoCurrencyOfDeal
{
    public function get(string $messageFromBot): string|false
    {
        $amountKeywords = require_once APP_PATH .'/config/amountKeywords.php';

        foreach ($amountKeywords as $word) {
            if (str_contains($messageFromBot, $word)) {
                return $word;
            }
        }

        return false;
    }

}