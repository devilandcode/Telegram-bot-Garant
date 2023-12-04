<?php

namespace App\Services\HomeServices;

use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Services\HomeServices\Handlers\ProfileInfoHomeHandler;

class HomeService
{
    public function __construct(
        private Messages $botAnswer,
        private Keyboards $botKeyboards,
        private ProfileInfoHomeHandler $profileInfoHomeHandler,
    )
    {
    }

    public function sendStartMenu()
    {
        $this->botKeyboards->start();
    }

    public function sendProfileInfo()
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
}