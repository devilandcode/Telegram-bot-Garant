<?php

namespace App\Services\CallBackService\Handlers;

use App\Kernel\HTTP\CryptoApi;

class GetCryptoPrice
{
    public function getCryptoCurrencyPrice()
    {
        $cryptoApi = new CryptoApi();
        $btcPrice   = $cryptoApi->getBtcCurrency()->btcPrice;
        $ethPrice   = $cryptoApi->getEthCurrency()->ethPrice;
        $usdtPrice  = $cryptoApi->getUSDTCurrency()->usdtPrice;

        return compact('btcPrice','ethPrice','usdtPrice');
    }
}