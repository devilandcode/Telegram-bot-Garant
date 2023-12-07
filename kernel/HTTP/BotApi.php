<?php

namespace App\Kernel\HTTP;

use App\Kernel\Exeptions\AnswerFromTelegramExeption;
use GuzzleHttp\Client;

class BotApi extends Client implements BotapiInterface
{
    /**
     *
     * @description A small class to work with telegram API.
     * Basic functions for sending messages and a little more...
     * This class is described under php version 8+, to use it on earlier versions, you need to
     * remove the description of return values of methods, for example : array
     */
 
    protected string $conftoken;
    public \stdClass $basicChatData;
 
    public function __construct($token)
    {
        parent::__construct();
        $this->conftoken = $token;
        $this->basicChatData = $this->phpInput();

    }
 

    public function phpInput(): \stdClass
    {
        return json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    }
 

    public function sendMessage(string $message): mixed
    {
        $params = array('chat_id' => $this->basicChatData->message->chat->id,'text'=>$message);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }


    public function sendMessageToUser(string $chat_id, string $message): mixed
    {
        $params = array('chat_id' => $chat_id,'text'=>$message);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }


    public function sendCallBackAnswer(string $message): mixed
    {
        $params = array('callback_query_id' => $this->basicChatData->callback_query->id,'text'=>$message);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/answerCallbackQuery",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageCallBack(string $message): mixed
    {
        $params = array('chat_id' => $this->basicChatData->callback_query->from->id,'text'=>$message,);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageWithInlineKeyboard(string $message, array $inlineKeyboard): mixed
    {
            $params = array('chat_id' => $this->basicChatData->message->chat->id,'text'=>$message,'reply_markup' => json_encode(array('inline_keyboard'=>$inlineKeyboard)));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);

            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);     

    }


    public function sendMessageWithInlineKeyboardToUser(string $chat_id, string $message, array $inlineKeyboard): mixed
    {
            $params = array('chat_id' => $chat_id,'text'=>$message,'reply_markup' => array('inline_keyboard'=>$inlineKeyboard));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageWithBaseKeyboard(string $message, array $keyboard): mixed
    {
            $params = array('chat_id' => $this->basicChatData->message->chat->id,'text' => $message, 'reply_markup' => array('keyboard' => $keyboard, 'resize_keyboard' => true, 'is_persistent' => true));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function sendMessageWithBaseKeyboardCallBack(string $message, array $keyboard): mixed
    {
            $params = array('chat_id' => $this->basicChatData->callback_query->from->id,'text' => $message, 'reply_markup' => array('keyboard' => $keyboard, 'resize_keyboard' => true, 'is_persistent' => true));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}