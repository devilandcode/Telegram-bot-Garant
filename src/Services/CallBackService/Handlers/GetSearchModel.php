<?php

namespace App\Services\CallBackService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Models\Search;
use App\Services\UserRepository\UserRepository;

class GetSearchModel
{
    public function get(int $numberOfDeal, UserRepository $repository, ConfigInterface $config): Search
    {

        $getBuyersLastSearchedArray = $repository->getDataOfDeal($numberOfDeal);

        /** Get Names of columns at search_history table*/
        $id = $config->get('database.search_name_of_primary_key');
        $idBuyer = $config->get('database.search_name_of_column_with_id_buyer');
        $idSeller = $config->get('database.search_name_of_column_with_id_seller');
        $amount = $config->get('database.search_name_of_column_with_crypto_amount');
        $currency = $config->get('database.search_name_of_column_with_crypto_currency');
        $terms = $config->get('database.search_name_of_column_with_terms_of_deal');
        $startTime = $config->get('database.search_name_of_column_with_start_search_time');

        /** @var Search */
        return new Search(
            $getBuyersLastSearchedArray[$id],
            $getBuyersLastSearchedArray[$idBuyer],
            $getBuyersLastSearchedArray[$idSeller],
            $getBuyersLastSearchedArray[$amount],
            $getBuyersLastSearchedArray[$currency],
            $getBuyersLastSearchedArray[$terms],
            $getBuyersLastSearchedArray[$startTime]
        );
    }

}