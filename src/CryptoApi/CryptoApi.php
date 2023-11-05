<?php

namespace App\CryptoApi;
use GuzzleHttp\Client;

class CryptoApi extends Client
{
    public $btcPrice;
    public $ethPrice;

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
}