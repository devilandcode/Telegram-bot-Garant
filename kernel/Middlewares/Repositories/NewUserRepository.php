<?php

namespace App\Kernel\Middlewares\Repositories;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\Database\DBDriver;

class NewUserRepository
{
    public function __construct(
        private DBDriver $db,
        private ConfigInterface $config
    )
    {
    }

    public function checkIsUserExist($idTelegram): bool
    {
        $nameOfUsersTable = $this->config->get('database.name_of_users_table');
        $nameOfColumnIdTelegram = $this->config->get('database.users_name_of_column_with_id_telegram');

        $sql = sprintf('SELECT * FROM %s WHERE %s = :%s',
            $nameOfUsersTable,
            $nameOfColumnIdTelegram,
            $nameOfColumnIdTelegram
        );

        $stm = $this->db->select($sql, [$nameOfColumnIdTelegram => $idTelegram], DBDriver::FETCH_ONE);

        return is_array($stm) ? true : false;
    }

    public function addNewUserToTable(string $idTelegram, string $username, string $idChat): string|false
    {
        $nameOfUsersTable = $this->config->get('database.name_of_users_table');
        $nameOfColumnIdTelegram = $this->config->get('database.users_name_of_column_with_id_telegram');
        $nameOfColumnUsername = $this->config->get('database.users_name_of_column_with_username');
        $nameOfColumnIdChat = $this->config->get('database.users_name_of_column_with_id_chat');

        $params = [
            $nameOfColumnIdTelegram   => $idTelegram,
            $nameOfColumnUsername     => $username,
            $nameOfColumnIdChat       => $idChat
        ];

        return $this->db->insert($nameOfUsersTable, $params);
    }


}