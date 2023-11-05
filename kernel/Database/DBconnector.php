<?php

namespace App\Kernel\Database;

use App\Kernel\Config\ConfigInterface;
use PDO;

class DBconnector
{
    private static $instance;

    public static function getConnect() : \PDO
    {
        if(self::$instance == null) {
            return self::$instance = self::getPDO();
        }
        return self::$instance;
    }

    private static function getPDO(
        string $driver,
        string $host,
        string $database,
        string $username,
        string $password
    ) : \PDO
    {
        $dbh = sprintf('%s:host=%s;dbname=%s',$driver, $host, $database);
        $pdo = new PDO($dbh, $username, $password);
        $pdo->exec('SET NAMES UTF8');

        return $pdo;
    }
}