<?php

namespace App\Services\HomeService\Handlers;


use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\CryptoApi;

class ProfileInfoHomeHandler extends BaseHomeHandler
{
    public function __construct()
    {
        parent::__construct();
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