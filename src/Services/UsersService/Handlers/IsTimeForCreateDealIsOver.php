<?php

namespace App\Services\UsersService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Models\Search;

class IsTimeForCreateDealIsOver
{
    public function check(Search $searchModel, ConfigInterface $config): bool
    {
        /** Set up at config/timeToOpenDeal.php */
        $timeToOpenDeal = $config->get('timeToOpenDeal.time');

        $timeWhenDealStart = $searchModel->startTime();

        /** Get time in minutes*/
        $howLongTimeFromSearch = (time() - (int)$timeWhenDealStart)/60;

        if ($howLongTimeFromSearch > (int) $timeToOpenDeal) {
            return true;
        } else {
            return false;
        }
    }
}