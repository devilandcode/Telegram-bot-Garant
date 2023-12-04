<?php

namespace App\Controllers;

use App\Services\HomeServices\HomeService;

class HomeController
{

    public function __construct(
        private HomeService $service,
    )
    {
    }

    public function start(): void
    {
        $this->service->sendStartMenu();
    }

    public function profile(): void
    {
        $this->service->sendProfileInfo();
    }
//
//    public function search(): void
//    {
//        $this->botAnswer->askUserIdToSearch();
//    }
//
//    public function getMyDeals(): void
//    {
//        $this->botAnswer->activeDeals();
//    }
//
//    public function support(): void
//    {
//        $this->botAnswer->explainHowToUseBot();
//    }
//
//    public function unknownCommand(): void
//    {
//        $this->botAnswer->unknownCommand();
//    }

}