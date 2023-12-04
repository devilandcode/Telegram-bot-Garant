<?php

namespace App\Kernel\Middlewares;

interface MiddlewareInterface
{
    public function check(array $params): void;
}