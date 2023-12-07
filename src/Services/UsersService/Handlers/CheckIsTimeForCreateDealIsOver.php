<?php

namespace App\Services\UsersService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Services\UsersService\Repositories\UserRepository;

class CheckIsTimeForCreateDealIsOver
{
    public function check(UserRepository $repository, BotapiInterface $botapi, ConfigInterface $config): bool
    {
        $idBuyer = $botapi->phpInput()->message->from->id;
        $nameOfTimeColumnInSearchTable = $config->get('database.search_name_of_column_with_start_search_time');

        $getBuyersLastSearchedData = $repository->showLastSearchData($idBuyer);

        $getBuyersLastSearchedData = print_r($getBuyersLastSearchedData, true);

//        if (is_array($getBuyersLastSearchedData)) {
//            $timeWhenDealStart = $getBuyersLastSearchedData[$nameOfTimeColumnInSearchTable];

//        }
    }
}