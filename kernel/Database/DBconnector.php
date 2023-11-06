<?php

namespace App\Kernel\Database;

use App\Kernel\Config\ConfigInterface;
use PDO;

class DBconnector
{
    private $instance;
    public function __construct(
        private ConfigInterface $config
    )
    {
    }

    public function getConnect() : \PDO
    {
        if($this->instance === null) {
            return $this->instance = $this->getPDO();
        }
        return $this->instance;
    }

    private function getPDO() : \PDO
    {
        $driver = $this->config->get('database.driver');
        $host = $this->config->get('database.host');
        $database = $this->config->get('database.database');
        $username = $this->config->get('database.username');
        $password = $this->config->get('database.password');

        $dbh = sprintf('%s:host=%s;dbname=%s',$driver, $host, $database);
        $pdo = new PDO($dbh, $username, $password);
        $pdo->exec('SET NAMES UTF8');

        return $pdo;
    }
}