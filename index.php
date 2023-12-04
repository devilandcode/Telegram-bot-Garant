<?php

use App\Kernel\Bot;

ini_set('log_errors', 'On');
ini_set('error_log', 'errors.log');

define('APP_PATH', __DIR__);

require_once 'vendor/autoload.php';

$bot = new Bot();
$bot->run();
