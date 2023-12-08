<?php

namespace App\Services\CallBackService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Models\Search;
use App\Services\UsersService\Repositories\UserRepository;

class GetDealModel
{
    public function get(int $numberOfDeal, ConfigInterface $config, UserRepository $repository)
    {
        $delaData = $repository->getDataOfDeal($numberOfDeal);

        /** Get Names of columns at search_history table*/
        $id = $config->get('database.search_name_of_primary_key');
        $idBuyer = $config->get('database.search_name_of_column_with_id_buyer');
        $idSeller = $config->get('database.search_name_of_column_with_id_seller');
        $amount = $config->get('database.search_name_of_column_with_crypto_amount');
        $terms = $config->get('database.search_name_of_column_with_terms_of_deal');
        $startTime = $config->get('database.search_name_of_column_with_start_search_time');

        /** @var Search */
        return new Search(
            $delaData[$id],
            $delaData[$idBuyer],
            $delaData[$idSeller],
            $delaData[$amount],
            $delaData[$terms],
            $delaData[$startTime]
        );

    }
}