<?php

namespace App\Kernel\Container;

use App\CryptoApi\CryptoApi;
use App\DealModel;
use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Controller\Controller;
use App\Kernel\HTTP\BotApi;
use App\Kernel\Database\DBconnector;
use App\Kernel\Database\DBDriver;
use App\Kernel\Parser\ParserUserData;
use App\Kernel\Router\Router;
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
    public readonly Controller $controller;
    public readonly Router $router;

    public function __construct()
    {
        $this->setConfig();
        $this->registerServices($this->getBotToken());
    }

    public function setConfig()
    {
        $this->config = new Config();
    }
    public function getBotToken()
    {
        return $this->config->get('bot.token');
    }

    public function registerServices($botToken): void
    {
        $this->bot = new BotApi($botToken);
        $this->DBconnector = new DBconnector($this->config);
        $this->DBDriver = new DBDriver($this->DBconnector->getConnect());
        $this->parser = new ParserUserData($this->bot->getInputData());
        $this->keyboards = new Keyboards($this->config, $botToken);
        $this->messages = new Messages($this->bot,$this->config);
        $this->crypto = new CryptoApi();
        $this->userManager = new UserModel($this->DBDriver);
        $this->dealManager = new DealModel($this->DBDriver);
        $this->controller = new Controller($this->keyboards);
        $this->router = new Router(
            $this->bot,
            $this->config,
            $this->controller
        );
    }
}