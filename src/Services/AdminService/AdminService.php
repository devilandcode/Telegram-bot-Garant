<?php

namespace App\Services\AdminService;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Messages\Messages;
use App\Services\DealRepository\DealRepository;
use App\Services\UserRepository\UserRepository;

class AdminService
{
    public function __construct(
        private BotapiInterface $botapi,
        private Messages $botMessage,
        private UserRepository $userRepository,
        private DealRepository $dealRepository,
        private ConfigInterface $config
    )
    {
    }

    public function messageToAllUsers()
    {

    }

}