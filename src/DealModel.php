<?php

namespace App;

use App\Kernel\Database\DBDriver;

class DealModel
{
    const NAME_OF_DEALS_TABLE  = 'deals';
    const ID_OF_DEAL           = 'id_deal';
    const ID_BUYER             = 'id_buyer';
    const ID_SELLER            = 'id_seller';
    const AMOUNT_OF_DEAL       = 'amount';
    const TERMS_OF_DEAL        = 'text';
    const ID_DEAL_FIELD        = 'id_deal';
    const IS_RESOLVED_FIELD    = 'is_resolved';
    const RESOLVED             = '1';
    private DBDriver $pdo;

    public function __construct(DBDriver $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addDataToConfirmedDealTable(
        string $idOfDeal,
        string $buyerId,
        string $sellerId,
        string $amountOfDeal,
        string $termsOfDeal
    )
    {
        $params = [
            self::ID_OF_DEAL      => $idOfDeal,
            self::ID_BUYER        => $buyerId,
            self::ID_SELLER       => $sellerId,
            self::AMOUNT_OF_DEAL  => $amountOfDeal,
            self::TERMS_OF_DEAL   => $termsOfDeal
        ];

        return $this->pdo->insert(self::NAME_OF_DEALS_TABLE, $params);
    }

    public function markDealIsREsolved(string $idSearchTable)
    {
        $stm = $this->pdo->update(self::NAME_OF_DEALS_TABLE,
            [
                self::IS_RESOLVED_FIELD => self::RESOLVED
            ],
            [
                self::ID_DEAL_FIELD => $idSearchTable
            ]);

        return $stm;
    }
}