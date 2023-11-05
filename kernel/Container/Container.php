<?php

namespace App\Kernel\Container;

use App\CryptoApi\CryptoApi;
use App\DealModel;
use App\Kernel\Api\BotApi;
use App\Kernel\Database\DBconnector;
use App\Kernel\Database\DBDriver;
use App\Kernel\Parser\ParserUserData;
use App\Keyboards;
use App\Messages;
use App\UserModel;

class Container
{
    public BotApi $bot;
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
        $this->inputPhpData = $this->bot->getInputData();
        $this->parser = new ParserUserData($this->inputPhpData);
        $this->keyboards = new Keyboards($token);
        $this->messages = new Messages($token);
        $this->crypto = new CryptoApi();
        $this->userManager = new UserModel(new DBDriver(DBconnector::getConnect()));
        $this->dealManager = new DealModel(new DBDriver(DBconnector::getConnect()));
    }
}