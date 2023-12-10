<?php

namespace App\Controllers;


use App\Services\UsersService\UserService;

class UserController
{
    public function __construct(
        private UserService $service
    )
    {
    }

    public function analyze(string $messageFromBot)
    {
        if ($this->service->isTelegramId($messageFromBot)) {
            $this->service->handleSellerId($messageFromBot);
            return;
        }

        /** If exist amount keywords from config/amountKeywords.php */
        if ($this->service->hasAmountKeywords($messageFromBot)) {
            $this->service->handleAmmoutOfDeal($messageFromBot);
            return;
        }

        /** If exist deal keywords from config/dealKeywords.php */
        if ($this->service->hasDealKeyword($messageFromBot)) {
            $this->service->handleTermsOfDeal($messageFromBot);
            return;
        }


    }
}