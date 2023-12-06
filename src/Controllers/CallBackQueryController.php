<?php

namespace App\Controllers;

use App\Services\CallBackService\CallBackService;

class CallBackQueryController
{
    public function __construct(
        private CallBackService $service,
    )
    {
    }

    public function askToEnterAmountOfDeal()
    {
        $this->service->askBuyerToEnterAmountOfDeal();
    }

    public function sendCallBackAnswerToTelegram()
    {
        $this->service->sendCallBackAnswer();
    }
}