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

    public function messageToAllUsers(array $users, string $message)
    {
        $nameOfTelegramIdColumn = $this->config->get('database.users_name_of_column_with_id_telegram');

        foreach($users as $key => $value) {
            try {
                $this->botMessage->mailToBot($value[$nameOfTelegramIdColumn], $message);
            } catch (\Exception $e) {
                $err = $e->getMessage();
            }
        }
    }

    public function getAllTelegramIdOfAllUsers(): ?array
    {
        return $this->userRepository->getAllUsersID();
    }

    public function getMessageToAllUsers(): string
    {
        $message = trim($this->botapi->phpInput()->channel_post->text);

        return substr($message, 4);
    }

}