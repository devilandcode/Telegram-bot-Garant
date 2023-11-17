<?php

namespace App\Kernel\Router;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\Controller\Controller;
use App\Kernel\HTTP\BotApi;

class Router
{
    private string $botMessage;
    private string $botCallBackQuery;

     public function __construct(
         private BotApi $bot,
         private ConfigInterface $config,
         private Controller $controller
     )
     {
        $this->initBotMessage();
     }

     public function dispatch(string $message)
     {
         if ($this->botCallBackQuery !== 'NotSet') {
//             $this->dispatchCallbackQuery();
         }else {
             $this->dispatchBotMessage($this->botMessage);
         }
     }

     public function dispatchBotMessage(string $message)
     {
         match ($message) {
             $this->config->get('messages.start') => call_user_func([$this->controller, 'start']),
         };
     }
     public function initBotMessage(): void
     {
        $this->botMessage = $this->bot->getMessage();
        $this->botCallBackQuery = $this->bot->getCallBackQuery();
        file_put_contents('call.txt', $this->botCallBackQuery, FILE_APPEND);
     }
}
