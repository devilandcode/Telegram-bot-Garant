<?php

namespace App\Services\CallBackService;

use App\Kernel\HTTP\BotApi;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Services\CallBackService\Handlers\GetCryptoPrice;

class CallBackService
{
    public function __construct(
        private BotApi $botApi,
        private Keyboards $botKeyboard,
        private Messages $botMessages,
    )
    {
    }

    public function askBuyerToEnterAmountOfDeal(): void
    {
        $arrayOfCryptoPrices = (new GetCryptoPrice())->getCryptoCurrencyPrice();
        extract($arrayOfCryptoPrices);

        $this->botMessages->askAmountOfDeal($btcPrice, $ethPrice, $usdtPrice);
    }

    public function sendCallBackAnswer(): void
    {
        $this->botApi->sendCallBackAnswer('');
    }
}