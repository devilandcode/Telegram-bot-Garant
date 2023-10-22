<?php

namespace App;

use \PDO;
class DBconnector
{   
    const HOST        = 'localhost';
    const DB_NAME     = 'bot';
    const DB_USERNAME = 'root_bot';
    const DB_PASS     = 'root106616';
    private static $instance;
    
    /**
     * MAKE STATIC INSTANCE OF CONNECTION
     *
     * @return \PDO
     */
    public static function getConnect() : \PDO 
    {
        if(self::$instance == null) {
            return self::$instance = self::getPDO();
        }
        return self::$instance;
    }

    /**
     * GET PDO INSTANCE
     *
     * @return \PDO
     */
    private static function getPDO() : \PDO
    {
        $dbh = sprintf('mysql:host=%s;dbname=%s', self::HOST, self::DB_NAME);
        $pdo = new PDO($dbh, self::DB_USERNAME, self::DB_PASS);
        $pdo->exec('SET NAMES UTF8');

        return $pdo;
    }
}