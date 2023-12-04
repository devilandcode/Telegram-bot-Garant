<?php

namespace App\Services\HomeServices\Handlers;

use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\CryptoApi;

class BaseHomeHandler
{
    public function __construct(
        protected BotApi $botApi,
        protected CryptoApi $cryptoApi,
    )
    {
    }

}