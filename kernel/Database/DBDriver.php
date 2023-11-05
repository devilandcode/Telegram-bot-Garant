<?php

namespace App\Kernel\Database;

use PDO;

class DBDriver
{
    const FETCH_ALL = 'all';
    const FETCH_ONE = 'one';

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * MAKE FETCH OR FETCHALL FROM TABLE DEPENDING OF INPUT PARAMS
     *
     * @param string $sql
     * @param array $params
     * @param [type] $fetch
     * @return mixed
     */
    public function select(string $sql, array $params = [], $fetch = self::FETCH_ALL) : mixed
    {
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);

        return $fetch === self::FETCH_ALL ? $stm->fetchAll(PDO::FETCH_ASSOC) : $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * INSERT INTO ANY TABLE DEPENDING OF INPUT PARAMS
     *
     * @param string $table
     * @param array $params
     * @return string|false
     */
    public function insert(string $table, array $params) : string|false
    {
        $columns = sprintf('(%s)', implode(',', array_keys($params)));
        $masks   = sprintf('(:%s)', implode(', :', array_keys($params)));

        $sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $columns, $masks);
        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);

        return $this->pdo->lastInsertId();
    }

    /**
     * UPDATE ANY TABLE DEPENDING OF INPUT PARAMS
     *
     * @param string $table
     * @param array $params
     * @param array $where
     * @return integer Returns the number of rows affected by the last SQL statement 
     */
    public function update(string $table, array $params, array $where) : int
    {   
        foreach($params as $k => $v) {
            $arrayParams[] = "$k = :$k";
        }

        foreach($where as $k => $v) {
            $arrayWhere[] = "$k = :$k";
        }

        $setParams   = sprintf('%s', implode(',', array_values($arrayParams)));
        $setWhere    = sprintf('%s', implode(',', array_values($arrayWhere)));
        $mergeArrays = array_merge($params, $where);

        $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $setParams, $setWhere);
        $stm = $this->pdo->prepare($sql);
        $stm->execute($mergeArrays);  
        
        return $stm->rowCount();
    }

    public function delete(string $table, array $where) : int
    {
        foreach($where as $k => $v) {
            $arrayWhere[] = "$k = :$k";
        }

        $setWhere = sprintf('%s', implode(',', array_values($arrayWhere)));
        $sql      = sprintf('DELETE FROM %s WHERE %s', $table, $setWhere);
        $stm      = $this->pdo->prepare($sql);
        $stm->execute($where);

        return $stm->rowCount();

    }
}    