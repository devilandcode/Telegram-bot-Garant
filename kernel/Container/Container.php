<?php

namespace App\Kernel\Container;

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Controller\Controller;
use App\Kernel\Database\DBconnector;
use App\Kernel\Database\DBDriver;
use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\CryptoApi;
use App\Kernel\Middlewares\AddIfNewUser;
use App\Kernel\Middlewares\MiddlewareInterface;
use App\Kernel\Middlewares\Repositories\NewUserRepository;
use App\Kernel\Middlewares\StopIfUsernameNotExist;
use App\Kernel\Parser\ParserUserData;
use App\Kernel\Router\Router;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Models\DealModel;
use App\Services\HomeService\HomeService;
use App\Services\UsersService\Repositories\UserRepository;
use App\Services\UsersService\UserService;

class Container
{
    public readonly BotApi $botApi;
    public readonly CryptoApi $cryptoApi;
    public readonly ConfigInterface $config;
    public readonly Messages $botMessages;
    public readonly Keyboards $botKeyboards;
    public readonly DBconnector $DBconnector;

    public readonly DBDriver $DBDriver;

    public readonly NewUserRepository $newUserRepository;

    public readonly MiddlewareInterface $isNewUser;
    public readonly MiddlewareInterface $isUsernameExist;
    public readonly ParserUserData $parser;
    public readonly HomeService $homeService;
    public readonly HomeController $homeController;
    public readonly UserService $userService;
    public readonly UserController $userController;
    public readonly UserRepository  $userRepository;
    public readonly DealModel $dealManager;




    public readonly Controller $adminController;
    public readonly Router $router;

    public function __construct()
    {
        $this->setConfig();
        $this->registerServices($this->getBotToken());
    }


    public function registerServices($botToken): void
    {
        $this->botApi = new BotApi($botToken);
        $this->cryptoApi = new CryptoApi();
        $this->botMessages = new Messages($this->botApi, $this->config);
        $this->botKeyboards = new Keyboards($this->botApi, $this->config);
        $this->DBconnector = new DBconnector($this->config);
        $this->DBDriver = new DBDriver($this->DBconnector->getConnect());
        $this->newUserRepository = new NewUserRepository($this->DBDriver, $this->config);
        $this->isNewUser = new AddIfNewUser($this->newUserRepository);
        $this->isUsernameExist = new StopIfUsernameNotExist($this->botMessages);
        $this->parser = new ParserUserData($this->botApi->phpInput());
        $this->homeService = new HomeService($this->botMessages, $this->botKeyboards);
        $this->homeController = new HomeController($this->homeService);
        $this->userService = new UserService();
        $this->userController = new UserController($this->userService);
//        $this->keyboards = new Keyboards($this->config, $botToken);
//        $this->messages = new Messages($this->botApi,$this->config);
//        $this->cryptoApi = new CryptoApi();
//        $this->userRepository = new UserRepository($this->DBDriver);
//        $this->dealManager = new DealModel($this->DBDriver);
//        $this->parser = new ParserUserData(
//            $this->botApi->getInputData(),
//            $this->userRepository,
//            $this->dealManager
//        );
//
//        $this->userController = new UserController(
//            $this->botApi,
//            $this->keyboards,
//            $this->messages,
//            $this->cryptoApi,
//            $this->userRepository,
//            $this->parser,
//            $this->config,
//            $this->dealManager
//        );
//        $this->adminController = new AdminController(
//            $this->botApi,
//            $this->keyboards,
//            $this->messages,
//            $this->cryptoApi,
//            $this->userRepository,
//            $this->parser,
//            $this->config,
//            $this->dealManager
//        );
        $this->router = new Router(
            $this->botApi->phpInput(),
            $this->isUsernameExist,
            $this->isNewUser,
            $this->config,
            $this->homeController,
            $this->userController,
        );
    }

    private function setConfig()
    {
        $this->config = new Config();
    }
    private function getBotToken()
    {
        return $this->config->get('bot.token');
    }
}