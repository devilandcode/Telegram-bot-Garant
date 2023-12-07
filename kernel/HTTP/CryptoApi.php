<?php

namespace App\Kernel\HTTP;
use GuzzleHttp\Client;

class CryptoApi extends Client implements CryptoapiInterface
{
    public $btcPrice;
    public $ethPrice;
    public $usdtPrice;

    /**
     * Get BTC currency
     *
     * @return $this
     */
    public function getBtcCurrency() 
    {
        $response = $this->request('POST', "https://api.coinlore.net/api/ticker/?id=90",['http_errors' => false]);
        $arrayBtc = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->btcPrice = isset($arrayBtc['0']['price_usd']) ? $arrayBtc['0']['price_usd'] : 'Search btc';
        return $this;
    }

    /**
     * Get ETH currency
     *
     * @return $this
     */
    public function getEthCurrency() 
    {
        $response = $this->request('POST', "https://api.coinlore.net/api/ticker/?id=80",['http_errors' => false]);
        $arrayEth = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->ethPrice = isset($arrayEth['0']['price_usd']) ? $arrayEth['0']['price_usd'] : 'Search eth';
        return $this;
    }

    public function getUSDTCurrency()
    {
        $response = $this->request('POST', "https://api.coinlore.net/api/ticker/?id=518",['http_errors' => false]);
        $arrayEth = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->usdtPrice = isset($arrayEth['0']['price_usd']) ? $arrayEth['0']['price_usd'] : 'Search usdt';
        return $this;
    }
}