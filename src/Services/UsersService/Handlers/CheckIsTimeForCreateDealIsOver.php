<?php

namespace App\Services\UsersService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Services\UsersService\Repositories\UserRepository;

class IsTimeForCreateDealIsOver
{
    public function check(UserRepository $repository, BotapiInterface $botapi, ConfigInterface $config): bool
    {
        $idBuyer = $botapi->phpInput()->message->from->id;

        /** Name in search_history table, in my case its "time_in" */
        $nameOfTimeColumnInSearchTable = $config->get('database.search_name_of_column_with_start_search_time');

        /** Set up at config/timeToOpenDeal.php */
        $timeToOpenDeal = $config->get('timeToOpenDeal.time');

        /** @var array|null $getBuyersLastSearchedData */
        $getBuyersLastSearchedData = $repository->showLastSearchData($idBuyer);

        $timeWhenDealStart = '';
        if (is_array($getBuyersLastSearchedData)) {
            $timeWhenDealStart = $getBuyersLastSearchedData[$nameOfTimeColumnInSearchTable];
        }

        /** Get time in minutes*/
        $howLongTimeFromSearch = (time() - (int)$timeWhenDealStart)/60;

        if ($howLongTimeFromSearch > (int) $timeToOpenDeal) {
            return true;
        } else {
            return false;
        }
    }
}