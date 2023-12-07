<?php

namespace App\Services\HomeService\Handlers;

use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\BotapiInterface;
use App\Kernel\HTTP\CryptoApi;
use App\Kernel\HTTP\CryptoapiInterface;

class BaseHomeHandler
{
    protected BotapiInterface $botApi;
    protected CryptoapiInterface $cryptoApi;
    protected ConfigInterface $config;
    public function __construct(
    )
    {
        $this->config = new Config();
        $this->botApi = new BotApi($this->config->get('bot.token'));
        $this->cryptoApi = new CryptoApi();
    }

}