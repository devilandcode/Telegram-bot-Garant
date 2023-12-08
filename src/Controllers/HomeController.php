<?php

namespace App\Controllers;

use App\Services\HomeService\HomeService;

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

    public function search(): void
    {
        $this->service->sendSearchStartMessage();
    }

    public function getMyDeals(): void
    {
        $this->service->sendUserDealsInfo();
    }

    public function support(): void
    {
        $this->service->sendBotInstruction();
    }
}