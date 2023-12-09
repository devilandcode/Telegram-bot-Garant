<?php

namespace App\Services\UsersService\Handlers;

class GetCryptoCurrencyOfDeal
{
    public function get(string $messageFromBot): string|false
    {
        $amountKeywords = require APP_PATH .'/config/amountKeywords.php';

        foreach ($amountKeywords as $word) {
            file_put_contents('res.txt', $word . "\n", FILE_APPEND);
            if (str_contains($messageFromBot, $word)) {
                return $word;
            }
        }

        return false;
    }

}