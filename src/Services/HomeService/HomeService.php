<?php

namespace App\Services\HomeService;

use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Services\HomeService\Handlers\ProfileInfoHomeHandler;

class HomeService
{
    private ProfileInfoHomeHandler $profileInfoHomeHandler;
    public function __construct(
        private Messages $botAnswer,
        private Keyboards $botKeyboards,
    )
    {
        $this->profileInfoHomeHandler = new ProfileInfoHomeHandler();
    }

    public function sendStartMenu(): void
    {
        $this->botKeyboards->start();
    }

    public function sendProfileInfo(): void
    {
        $profileArray = $this->profileInfoHomeHandler->getProfileInfo();
        extract($profileArray);

        $this->botAnswer->sendMyProfileData(
            $idTelegram,
            $username,
            $btcPrice,
            $ethPrice,
            $usdtPrice,
        );
    }

    public function sendSearchStartMessage()
    {
        $this->botAnswer->askUserIdToSearch();
    }

    public function sendUserDealsInfo()
    {
        $this->botAnswer->activeDeals();
    }

    public function sendBotInstruction()
    {
        $this->botAnswer->explainHowToUseBot();
    }
}