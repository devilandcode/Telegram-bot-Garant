<?php

namespace App\Kernel\Container;

use App\Controllers\AdminController;
use App\Controllers\CallBackQueryController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Database\DBconnector;
use App\Kernel\Database\DBDriver;
use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\CryptoApi;
use App\Kernel\Middlewares\AddIfNewUser;
use App\Kernel\Middlewares\Repositories\NewUserRepository;
use App\Kernel\Middlewares\StopIfUsernameNotExist;
use App\Kernel\Router\Router;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Services\AdminService\AdminService;
use App\Services\CallBackService\CallBackService;
use App\Services\DealRepository\DealRepository;
use App\Services\HomeService\HomeService;
use App\Services\UserRepository\UserRepository;
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

    public readonly AddIfNewUser $isNewUser;
    public readonly StopIfUsernameNotExist $isUsernameExist;
    public readonly HomeService $homeService;
    public readonly HomeController $homeController;
    public readonly UserService $userService;
    public readonly UserController $userController;
    public readonly UserRepository  $userRepository;
    public readonly DealRepository  $dealRepository;
    public readonly CallBackService  $callBackService;
    public readonly CallBackQueryController $callBackQueryController;
    public readonly AdminService $adminService;
    public readonly AdminController $adminController;
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
        $this->homeService = new HomeService($this->botMessages, $this->botKeyboards);
        $this->homeController = new HomeController($this->homeService);
        $this->userRepository = new UserRepository($this->DBDriver);
        $this->dealRepository = new DealRepository($this->DBDriver);
        $this->userService = new UserService(
            $this->botApi,
            $this->config,
            $this->botKeyboards,
            $this->botMessages,
            $this->userRepository
        );
        $this->callBackService = new CallBackService(
            $this->botApi,
            $this->botKeyboards,
            $this->botMessages,
            $this->userRepository,
            $this->dealRepository,
            $this->config
        );
        $this->userController = new UserController($this->userService);
        $this->callBackQueryController = new CallBackQueryController($this->callBackService);
        $this->adminService = new AdminService(
            $this->botApi,
            $this->botMessages,
            $this->userRepository,
            $this->dealRepository,
            $this->config
        );
        $this->adminController = new AdminController($this->adminService);
        $this->router = new Router(
            $this->botApi->phpInput(),
            $this->isUsernameExist,
            $this->isNewUser,
            $this->config,
            $this->homeController,
            $this->userController,
            $this->callBackQueryController,
            $this->adminController
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