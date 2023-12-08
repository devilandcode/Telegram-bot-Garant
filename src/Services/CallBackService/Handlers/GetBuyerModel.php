<?php

namespace App\Services\CallBackService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Models\Buyer;
use App\Services\UsersService\Repositories\UserRepository;

class GetBuyerModel
{
    public function get(string $idTelegram, ConfigInterface $config, UserRepository $repository)
    {
        /** Get Names of columns from users table*/
        $id = $config->get('database.users_name_of_primary_key');
        $id_telegram = $config->get('database.users_name_of_column_with_id_telegram');
        $username = $config->get('database.users_name_of_column_with_username');
        $idChat = $config->get('database.users_name_of_column_with_id_chat');
        $isModerate = $config->get('database.users_name_of_column_with_moderate');

        $getBuyerDataFromUsersTable = $repository->getUserInfoById($idTelegram);

        /** @var Buyer */
        return new Buyer(
            $getBuyerDataFromUsersTable[$id],
            $getBuyerDataFromUsersTable[$id_telegram],
            $getBuyerDataFromUsersTable[$username],
            $getBuyerDataFromUsersTable[$idChat],
            $getBuyerDataFromUsersTable[$isModerate],
        );
    }

}