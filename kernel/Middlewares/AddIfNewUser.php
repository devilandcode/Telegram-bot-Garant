<?php

namespace App\Kernel\Middlewares;

use App\Kernel\Middlewares\Repositories\NewUserRepository;

class AddIfNewUser implements MiddlewareInterface
{
    public function __construct(
        private NewUserRepository $repository
    )
    {
    }

    public function check(array $userInfo): void
    {
        extract($userInfo);

        if ($this->repository->checkIsUserExist($idTelegram) === false) {

            $this->repository->addNewUserToTable($idTelegram, $username, $idChat);
        }
    }
}