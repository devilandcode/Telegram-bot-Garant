<?php

namespace App\Kernel\HTTP;

interface CryptoapiInterface
{
    public function getBtcCurrency();
    public function getEthCurrency() ;
    public function getUSDTCurrency();
}