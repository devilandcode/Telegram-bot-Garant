<?php

namespace App;
use App\Kernel\Database\DBDriver;

class UserModel
{   
    const  NAME_OF_USER_TABLE   = 'users';
    const  NAME_OF_SEACH_TABLE  = 'searchHistory';
    const  ID_OF_SEARCH_TABLE   = 'id';
    const  ID_TELEGRAM          = 'id_telegram';
    const  ID_BUYER             = 'id_buyer';
    const  TERMS_OF_DEAL        = 'text';
    const  USERNAME             = 'username';
    const  CHAT_ID              = 'chat_id';
    const  AMOUNT_OF_DEAL       = 'amount';
    const  ID_SELLER             = 'id_seller';
    const TIME_WHEN_SEARCHED    = 'time_in';
    private DBDriver $pdo;

    public function __construct(DBDriver $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * ADD TO USER'S TABLE IF ITS NEW USER
     *
     * @param string $id_telegram
     * @param string $username
     * @param string $chat_id
     * @return string|false
     */
    public function addToUserTable(string $id_telegram, string $username, string $chat_id) : string|false
    {
        $params = [
            self::ID_TELEGRAM   => $id_telegram,
            self::USERNAME      => $username,
            self::CHAT_ID       => $chat_id
        ];

        return $this->pdo->insert(self::NAME_OF_USER_TABLE, $params);
    }

    /**
     * ADD TO SEARCH TABLE 
     *
     * @param string $id_telegram
     * @param string $idToLookUp
     * @return mixed
     */
    public function addToSearchTable(string $id_telegram, string $idSeller) : mixed
    {   
        $time_in = time();
        $params = [
            self::ID_BUYER                    => $id_telegram,
            self::ID_SELLER                   => $idSeller,
            self::TIME_WHEN_SEARCHED          => $time_in
        ];

        return $this->pdo->insert(self::NAME_OF_SEACH_TABLE, $params);
    }

    public function removeFromUsersTable(string $id_telegram): int
    {
        return $this->pdo->delete(self::NAME_OF_USER_TABLE,
            [
                self::ID_TELEGRAM => $id_telegram
            ]
        );
    }

    /**
     * GET ALL TELEGRAM ID FROM USER TABLE
     *
     * @return array|null
     */
    public function getAllUsersID() : ?array
    {
        $sql = sprintf('SELECT * FROM %s', self::NAME_OF_USER_TABLE);
        $stm = $this->pdo->select($sql);

        return is_array($stm) ? $stm : null;
    }
    /**
     * GET USER INFO BY TELEGRAM ID (USER ID)
     *
     * @param string $id_telegram
     * @return array|null
     */
    public function getUserInfoById($id_telegram) : ?array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s', self::NAME_OF_USER_TABLE, 
        self::ID_TELEGRAM, self::ID_TELEGRAM);
        $stm = $this->pdo->select($sql, [self::ID_TELEGRAM => $id_telegram], DBDriver::FETCH_ONE);

        return is_array($stm) ? $stm : null;
    }

    /**
     * GET INFO OF SELLER (WHICH SEARCHED FOR LAST)
     *
     * @param string $id_telegram
     * @return mixed
     */
    public function getDataOfSeller($id_telegram) : mixed
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1', 
        self::NAME_OF_SEACH_TABLE, self::ID_BUYER, self::ID_BUYER);
        $stm = $this->pdo->select($sql, [self::ID_BUYER => $id_telegram], DBDriver::FETCH_ONE);

        return is_array($stm) ? $stm : null;
    }

    /**
     * GET INFO OF BUYER (WHICH SEARCHED FOR LAST)
     *
     * @param $id_telegram
     * @return array|null
     */
    public function getDataOfBuyer($id_telegram)
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1', 
        self::NAME_OF_SEACH_TABLE, self::ID_SELLER, self::ID_SELLER);
        $stm = $this->pdo->select($sql, [self::ID_SELLER => $id_telegram], DBDriver::FETCH_ONE);

        return is_array($stm) ? $stm : null;
    }

    public function getDataOfDeal(string $idOfDeal)
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s ORDER BY dt DESC LIMIT 1',
            self::NAME_OF_SEACH_TABLE, self::ID_OF_SEARCH_TABLE, self::ID_OF_SEARCH_TABLE);
        $stm = $this->pdo->select($sql, [self::ID_OF_SEARCH_TABLE => $idOfDeal], DBDriver::FETCH_ONE);

        return is_array($stm) ? $stm : null;
    }

    /**
     * ADD CRYPTO AMOUNT OF DEAL TO SEARCH'S TABLE
     *
     * @param string $amount
     * @param string $idSearchTable
     * @return int   Returns the number of rows affected by the last SQL statement 
     */
    public function addCryptoAmountToSeacrhTable(string $amount, string $idSearchTable)
    {
        $stm = $this->pdo->update(self::NAME_OF_SEACH_TABLE,
            [
                self::AMOUNT_OF_DEAL => $amount
            ],
            [
                self::ID_OF_SEARCH_TABLE => $idSearchTable
            ] );
        return $stm;    
    }

    /**
     * ADD DESCRIPTION OF DEAL TO SEARCH'S TABLE
     *
     * @param string $text
     * @param string $idSearchTable
     * @return int   Returns the number of rows affected by the last SQL statement 
     */ 
    public function addTermsOfDealToSearchTable(string $text, string $idSearchTable)
    {
        $stm = $this->pdo->update(self::NAME_OF_SEACH_TABLE,
            [
                self::TERMS_OF_DEAL => $text
            ],
            [
                self::ID_OF_SEARCH_TABLE => $idSearchTable
            ]);

        return $stm;    
    }
}