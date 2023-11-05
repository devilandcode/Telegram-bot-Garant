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
    public BotApi $bot;
    public ConfigInterface $config;
    public ParserUserData $parser;
    public UserModel $userManager;
    public DealModel $dealManager;
    public Keyboards $keyboards;
    public Messages $messages;
    public CryptoApi $crypto;

    public function __construct($token)
    {
        $this->registerServices($token);
    }

    public function registerServices($token): void
    {
        $this->bot = new BotApi($token);
        $this->config = new Config();
        $this->parser = new ParserUserData($this->bot->getInputData());
        $this->keyboards = new Keyboards($token);
        $this->messages = new Messages($token);
        $this->crypto = new CryptoApi();
        $this->userManager = new UserModel(new DBDriver(DBconnector::getConnect()));
        $this->dealManager = new DealModel(new DBDriver(DBconnector::getConnect()));
    }
}