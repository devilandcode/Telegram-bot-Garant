<?php

namespace App\Services\HomeServices\Handlers;


use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\CryptoApi;

class ProfileInfoHomeHandler extends BaseHomeHandler
{
    public function __construct(BotApi $botApi, CryptoApi $cryptoApi)
    {
        parent::__construct($botApi, $cryptoApi);
    }

    public function getProfileInfo(): array
    {
        $idTelegram = $this->botApi->phpInput()->message->from->id;
        $username   = $this->botApi->phpInput()->message->from->username;
        $btcPrice   = $this->cryptoApi->getBtcCurrency()->btcPrice;
        $ethPrice   = $this->cryptoApi->getEthCurrency()->ethPrice;
        $usdtPrice  = $this->cryptoApi->getUSDTCurrency()->usdtPrice;

        return compact('idTelegram','username','btcPrice','ethPrice','usdtPrice');
    }
}