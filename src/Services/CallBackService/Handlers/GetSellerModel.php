<?php

namespace App\Services\CallBackService\Handlers;

use App\Kernel\Config\ConfigInterface;
use App\Models\Seller;
use App\Services\UsersService\Repositories\UserRepository;

class GetSellerModel
{
    public function get(string $sellerIdTelegram, ConfigInterface $config, UserRepository $repository)
    {
        /** Get Names of columns from users table*/
        $id = $config->get('database.users_name_of_primary_key');
        $id_telegram = $config->get('database.users_name_of_column_with_id_telegram');
        $username = $config->get('database.users_name_of_column_with_username');
        $idChat = $config->get('database.users_name_of_column_with_id_chat');
        $isModerate = $config->get('database.users_name_of_column_with_moderate');

        $getSellerDataFromUsersTable = $repository->getUserInfoById($sellerIdTelegram);

        /** @var Seller */
        return new Seller(
            $getSellerDataFromUsersTable[$id],
            $getSellerDataFromUsersTable[$id_telegram],
            $getSellerDataFromUsersTable[$username],
            $getSellerDataFromUsersTable[$idChat],
            $getSellerDataFromUsersTable[$isModerate],
        );
    }
}