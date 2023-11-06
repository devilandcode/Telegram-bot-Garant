<?php

namespace App\Kernel\Container;

use App\CryptoApi\CryptoApi;
use App\DealModel;
use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotApi;
use App\Kernel\Database\DBconnector;
use App\Kernel\Database\DBDriver;
use App\Kernel\Parser\ParserUserData;
use App\Keyboards;
use App\Messages;
use App\UserModel;

class Container
{
    public readonly BotApi $bot;
    public readonly DBconnector $DBconnector;

    public readonly DBDriver $DBDriver;
    public readonly ConfigInterface $config;
    public readonly ParserUserData $parser;
    public readonly UserModel $userManager;
    public readonly DealModel $dealManager;
    public readonly Keyboards $keyboards;
    public readonly Messages $messages;
    public readonly CryptoApi $crypto;

    public function __construct($token)
    {
        $this->registerServices($token);
    }

    public function registerServices($token): void
    {
        $this->bot = new BotApi($token);
        $this->config = new Config();
        $this->DBconnector = new DBconnector($this->config);
        $this->DBDriver = new DBDriver($this->DBconnector->getConnect());
        $this->parser = new ParserUserData($this->bot->getInputData());
        $this->keyboards = new Keyboards($this->config, $token);
        $this->messages = new Messages($token);
        $this->crypto = new CryptoApi();
        $this->userManager = new UserModel($this->DBDriver);
        $this->dealManager = new DealModel($this->DBDriver);
    }
}