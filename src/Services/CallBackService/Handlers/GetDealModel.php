<?php

namespace App\Services\CallBackService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Models\Buyer;
use App\Models\Deal;
use App\Models\Search;
use App\Models\Seller;


class GetDealModel
{
    public function get(Buyer $buyerModel, Seller $sellerModel, Search $searchModel, ConfigInterface $config)
    {
        $currency = $searchModel->currency();
        $cryptoWallet = $config->get("cryptoWallets.$currency");

        $garantPercent = $config->get('guarantorСommission.percent');
        $resultAmount = (float)($searchModel->amount())/100 * (100 +(int)$garantPercent);


        /** @var Deal */
        return new Deal(
            $searchModel->id(),
            $searchModel->idBuyer(),
            $buyerModel->username(),
            $searchModel->idSeller(),
            $sellerModel->username(),
            $searchModel->amount(),
            $searchModel->currency(),
            $resultAmount,
            $searchModel->terms(),
            $cryptoWallet,
        );

    }
}