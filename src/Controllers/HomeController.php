<?php

namespace App\Controllers;

class HomeController
{


    public function start(): void
    {
        $this->botKeyboard->start();
    }

    public function profile(): void
    {
        $this->botAnswer->sendMyProfileData(
            $this->parser->id_telegram,
            $this->parser->username,
            $this->cryptoApi->getBtcCurrency()->btcPrice,
            $this->cryptoApi->getEthCurrency()->ethPrice,
            $this->cryptoApi->getUSDTCurrency()->usdtPrice
        );
    }

    public function search(): void
    {
        $this->botAnswer->askUserIdToSearch();
    }

    public function getMyDeals(): void
    {
        $this->botAnswer->activeDeals();
    }

    public function support(): void
    {
        $this->botAnswer->explainHowToUseBot();
    }

    public function unknownCommand(): void
    {
        $this->botAnswer->unknownCommand();
    }

}