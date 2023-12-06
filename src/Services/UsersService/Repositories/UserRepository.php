<?php

namespace App\Services\UsersService\Repositories;

use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Database\DBDriver;

class UserRepository
{   
    private ConfigInterface $config;
    private string $searchTableName;
    private string $searchTablePrimaryKeyName;
    private string $buyerColumnName;
    private string $sellerColumnName;
    private string $nameOfAmountColumnInSearchTable;
    private string $nameOfTermsColumnInSearchTable;
    private string $timeColumnName;
    private string $usersTableName;
    private string $nameOfColumnIdTelegram;
    private string $nameOfColumnUsername;

    public function __construct(
        private DBDriver $pdo)
    {
        $this->config = new Config();
        $this->searchTableName  = $this->config->get('database.name_of_search_table');
        $this->buyerColumnName  = $this->config->get('database.search_name_of_column_with_id_buyer');
        $this->sellerColumnName = $this->config->get('database.search_name_of_column_with_id_buyer');
        $this->timeColumnName   = $this->config->get('database.search_name_of_column_with_id_buyer');
        $this->usersTableName   = $this->config->get('database.name_of_users_table');
        $this->nameOfColumnUsername = $this->config->get('database.users_name_of_column_with_username');
        $this->nameOfColumnIdTelegram = $this->config->get('database.users_name_of_column_with_id_telegram');
        $this->searchTablePrimaryKeyName = $this->config->get('database.search_name_of_primary_key');
        $this->nameOfAmountColumnInSearchTable = $this->config->get('database.search_name_of_column_with_crypto_amount');
        $this->nameOfTermsColumnInSearchTable = $this->config->get('database.search_name_of_column_with_terms_of_deal');

    }


    public function addToSearchTable(string $id_telegram, string $idSeller) : mixed
    {
        $time_in = time();
        $params = [
            $this->buyerColumnName   => $id_telegram,
            $this->sellerColumnName  => $idSeller,
            $this->timeColumnName    => $time_in
        ];

        return $this->pdo->insert($this->searchTableName, $params);
    }

    public function removeFromUsersTable(string $id_telegram): int
    {
        return $this->pdo->delete($this->usersTableName,
            [
                $this->nameOfColumnIdTelegram => $id_telegram
            ]
        );
    }

    public function getAllUsersID() : ?array
    {
        $sql = sprintf('SELECT id FROM %s', $this->usersTableName);
        $stm = $this->pdo->select($sql);

        return is_array($stm) ? $stm : null;
    }

    public function getUserInfoById($id_telegram) : array|bool
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s',
                    $this->usersTableName,
                    $this->nameOfColumnIdTelegram,
                    $this->nameOfColumnIdTelegram
                );

        $stm = $this->pdo->select($sql, [$this->nameOfColumnIdTelegram => $id_telegram], DBDriver::FETCH_ONE);
        return is_array($stm) ? $stm : false;
    }

    public function checkIsUserExistByTelegramId($id_telegram): bool
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s',
            $this->usersTableName,
            $this->nameOfColumnIdTelegram,
            $this->nameOfColumnIdTelegram
        );

        $stm = $this->pdo->select($sql, [$this->nameOfColumnIdTelegram => $id_telegram], DBDriver::FETCH_ONE);
        return is_array($stm) ? true : false;
    }


    public function showLastSearchData($id_telegram) : ?array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1', 
                    $this->searchTableName,
                    $this->buyerColumnName,
                    $this->buyerColumnName
                );

        $stm = $this->pdo->select($sql, [$this->buyerColumnName => $id_telegram], DBDriver::FETCH_ONE);
        return is_array($stm) ? $stm : null;
    }


    public function getDataOfSeller($id_telegram) : ?array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
                    $this->searchTableName,
                    $this->buyerColumnName,
                    $this->buyerColumnName
                );

        $stm = $this->pdo->select($sql, [$this->buyerColumnName => $id_telegram], DBDriver::FETCH_ONE);
        return is_array($stm) ? $stm : null;
    }

    public function getDataOfBuyer($id_telegram)
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
                    $this->searchTableName,
                    $this->sellerColumnName,
                    $this->sellerColumnName
                );

        $stm = $this->pdo->select($sql, [$this->sellerColumnName => $id_telegram], DBDriver::FETCH_ONE);
        return is_array($stm) ? $stm : null;
    }

    public function getDataOfDeal(int $idOfDeal)
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
                    $this->searchTableName,
                    $this->searchTablePrimaryKeyName,
                    $this->searchTablePrimaryKeyName
                );

        $stm = $this->pdo->select($sql, [$this->searchTablePrimaryKeyName => $idOfDeal], DBDriver::FETCH_ONE);
        return is_array($stm) ? $stm : null;
    }


    public function addCryptoAmountToSeacrhTable(string $amount, string $idSearchTable)
    {
        $stm = $this->pdo->update($this->searchTableName,
            [
                $this->nameOfAmountColumnInSearchTable => $amount
            ],
            [
                $this->searchTablePrimaryKeyName => $idSearchTable
            ] );

        return $stm;    
    }


    public function addTermsOfDealToSearchTable(string $text, string $idSearchTable)
    {
        $stm = $this->pdo->update($this->searchTableName,
            [
                $this->nameOfTermsColumnInSearchTable => $text
            ],
            [
                $this->searchTablePrimaryKeyName => $idSearchTable
            ]);

        return $stm;    
    }
}