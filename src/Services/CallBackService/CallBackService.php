<?php

namespace App\Services\CallBackService;

use App\Kernel\HTTP\BotApi;
use App\Keyboards\Keyboards;
use App\Messages\Messages;

class CallBackService
{
    public function __construct(
        private BotApi $botApi,
        private Keyboards $botKeyboard,
        private Messages $botMessages,
    )
    {
    }

    public function askBuyerToEnterAmountOfDeal()
    {
        $this->botMessages->askAmountOfDeal('', '', '');
    }

    public function sendCallBackAnswer()
    {
        $this->botApi->sendCallBackAnswer('');
    }
}