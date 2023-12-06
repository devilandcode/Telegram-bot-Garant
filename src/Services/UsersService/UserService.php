<?php

namespace App\Services\UsersService;

use App\Kernel\HTTP\BotApi;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Services\UsersService\Repositories\UserRepository;

class UserService
{

    public function __construct(
        private BotApi $botApi,
        private Keyboards $botKeyboards,
        private Messages $botMessages,
        private UserRepository $repository
    )
    {
    }

    public function isTelegramId(string $messageFromBot)
    {
        if (is_numeric($messageFromBot) && strlen($messageFromBot) === 10) {
            return true;
        }

        return false;
    }

    public function handleSellerId(string $sellerId)
    {
            if ($this->isUserExistInUsersTable($sellerId)) {

                $this->botKeyboards->showKeyboardUserExist();
                $this->repository->addToSearchTable(
                    $this->botApi->phpInput()->message->from->id,
                    $sellerId
                );

            }else {
                $this->botKeyboards->showGoBackKeyboard();
            }

    }

    private function isUserExistInUsersTable(string $telegramId): bool
    {
        return $this->repository->checkIsUserExistByTelegramId($telegramId);
    }


}