<?php

namespace App\Services\DealRepository;


use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Database\DBDriver;

class DealRepository
{

    private ConfigInterface $config;
    private string $nameOfDealTable;
    private string $nameOfColumnOfDealNumber;
    private string $nameOfColumnOfIdBuyer;
    private string $nameOfColumnOfBuyerUsername;
    private string $nameOfColumnOfIdSeller;
    private string $nameOfColumnOfSellerUsername;
    private string $nameOfColumnOfAmountOfDeal;
    private string $nameOfColumnOfCurrency;
    private string $nameOfColumnOfCryptoWallet;
    private string $nameOfColumnOfResultAmount;
    private string $nameOfColumnOfTermsOfDeal;


    public function __construct(
        private DBDriver $db)
    {
        $this->config = new Config();
        $this->nameOfDealTable = $this->config->get('database.name_of_deal_table');
        $this->nameOfColumnOfDealNumber = $this->config->get('database.deal_name_of_column_deal_id');
        $this->nameOfColumnOfIdBuyer = $this->config->get('database.deal_name_of_column_with_id_buyer');
        $this->nameOfColumnOfBuyerUsername = $this->config->get('database.deal_name_of_column_with_buyer_username');
        $this->nameOfColumnOfIdSeller = $this->config->get('database.deal_name_of_column_with_id_seller');
        $this->nameOfColumnOfSellerUsername = $this->config->get('database.deal_name_of_column_with_seller_username');
        $this->nameOfColumnOfAmountOfDeal = $this->config->get('database.deal_name_of_column_with_crypto_amount');
        $this->nameOfColumnOfCurrency = $this->config->get('database.deal_name_of_column_with_crypto_currency');
        $this->nameOfColumnOfResultAmount = $this->config->get('database.deal_name_of_column_with_result_amount');
        $this->nameOfColumnOfTermsOfDeal = $this->config->get('database.deal_name_of_column_with_terms_of_deal');
        $this->nameOfColumnOfCryptoWallet = $this->config->get('database.deal_name_of_column_with_crypto_wallet');

    }

    public function addDataToDealTable(
        string $idOfDeal,
        string $buyerId,
        string $buyerUsername,
        string $sellerId,
        string $sellerUsername,
        string $amountOfDeal,
        string $currencyOfDeal,
        string $cryptoWallet,
        string $resultAmount,
        string $termsOfDeal
    )
    {
        $params = [
            $this->nameOfColumnOfDealNumber      => $idOfDeal,
            $this->nameOfColumnOfIdBuyer         => $buyerId,
            $this->nameOfColumnOfBuyerUsername   => $buyerUsername,
            $this->nameOfColumnOfIdSeller        => $sellerId,
            $this->nameOfColumnOfSellerUsername  => $sellerUsername,
            $this->nameOfColumnOfAmountOfDeal    => $amountOfDeal,
            $this->nameOfColumnOfCurrency        => $currencyOfDeal,
            $this->nameOfColumnOfCryptoWallet    => $cryptoWallet,
            $this->nameOfColumnOfResultAmount    => $resultAmount,
            $this->nameOfColumnOfTermsOfDeal     => $termsOfDeal,
        ];

        return $this->db->insert($this->nameOfDealTable, $params);
    }

//    public function getDealData(int $idDeal): ?array
//    {
//        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
//            self::NAME_OF_DEALS_TABLE, self::ID_OF_DEAL, self::ID_OF_DEAL);
//        $stm = $this->db->select($sql, [self::ID_OF_DEAL => $idDeal], DBDriver::FETCH_ONE);
//
//        return is_array($stm) ? $stm : null;
//    }
//
//    public function checkIsDealMarkLikeResolved(int $idDeal): ?array
//    {
//        $sql = sprintf('SELECT %s FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
//             self::IS_RESOLVED_COLUMN, self::NAME_OF_DEALS_TABLE, self::ID_OF_DEAL, self::ID_OF_DEAL);
//        $stm = $this->db->select($sql, [self::ID_OF_DEAL => $idDeal], DBDriver::FETCH_ONE);
//
//        return is_array($stm) ? $stm : null;
//    }
//
//    public function markDealIsResolved(string $idSearchTable)
//    {
//        $stm = $this->db->update(self::NAME_OF_DEALS_TABLE,
//            [
//                self::IS_RESOLVED_FIELD => self::RESOLVED
//            ],
//            [
//                self::ID_DEAL_FIELD => $idSearchTable
//            ]);
//
//        return $stm;
//    }
}