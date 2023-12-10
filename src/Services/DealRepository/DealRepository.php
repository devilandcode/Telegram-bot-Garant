<?php

namespace App\Services\DealRepository;

use App\Kernel\Database\DBDriver;

class DealRepository
{

    private DBDriver $db;

    public function __construct(DBDriver $db)
    {
        $this->db = $db;
    }

    public function addDataToDealTable(
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

        return $this->db->insert(self::NAME_OF_DEALS_TABLE, $params);
    }

    public function getDealData(int $idDeal): ?array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
            self::NAME_OF_DEALS_TABLE, self::ID_OF_DEAL, self::ID_OF_DEAL);
        $stm = $this->db->select($sql, [self::ID_OF_DEAL => $idDeal], DBDriver::FETCH_ONE);

        return is_array($stm) ? $stm : null;
    }

    public function checkIsDealMarkLikeResolved(int $idDeal): ?array
    {
        $sql = sprintf('SELECT %s FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
             self::IS_RESOLVED_COLUMN, self::NAME_OF_DEALS_TABLE, self::ID_OF_DEAL, self::ID_OF_DEAL);
        $stm = $this->db->select($sql, [self::ID_OF_DEAL => $idDeal], DBDriver::FETCH_ONE);

        return is_array($stm) ? $stm : null;
    }

    public function markDealIsResolved(string $idSearchTable)
    {
        $stm = $this->db->update(self::NAME_OF_DEALS_TABLE,
            [
                self::IS_RESOLVED_FIELD => self::RESOLVED
            ],
            [
                self::ID_DEAL_FIELD => $idSearchTable
            ]);

        return $stm;
    }
}