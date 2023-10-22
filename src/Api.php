<?php

namespace App;

use GuzzleHttp\Client;
use JsonException;
 
class Api extends Client
{
    /**
     *
     * @description A small class to work with telegram API.
     * Basic functions for sending messages and a little more...
     * This class is described under php version 8+, to use it on earlier versions, you need to
     * remove the description of return values of methods, for example : array
     */
 
    protected string $conftoken;
    public array $basicChatData;
 
    public function __construct($token)
    {
        parent::__construct();
        $this->conftoken = $token;
        $this->basicChatData = $this->getInputData();
    }
 
 
    /**
     * GET INFO FROM PHP INPUT
     * 
     * @return mixed $result - basic chat data
     * @throws JsonException
     */
    public function getInputData(): mixed
    {
        return json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    }
 
    /**
     * GET MESSAGE FROM BOT
     * 
     * @return mixed - message which has sent to bot
     */
    public function getMessage(): mixed
    {
    return isset($this->basicChatData['message']['text']) ? $this->basicChatData['message']['text'] : 'smth wrong';
    }

    public function getMessageFromAdminChannel(): mixed
    {
        return isset($this->basicChatData['channel_post']['text']) ? $this->basicChatData['channel_post']['text'] : 'no messages from admin';
    }


    /**
     *
     * @param string $message - bot message
     * @return mixed - execution result
     * @throws JsonException
     *
     */
    public function sendMessage(string $message): mixed
    {
        $params = array('chat_id' => $this->basicChatData['message']['chat']['id'],'text'=>$message);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     *
     * @param string $message - bot message
     * @param string $chat_id - user message to whom to send
     * @return mixed - execution result
     * @throws JsonException
     *
     */
    public function sendMessageToUser(string $chat_id, string $message): mixed
    {
        $params = array('chat_id' => $chat_id,'text'=>$message);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
    *
    * @return mixed - returns data from the ['callback_query'] array
    * if there was a click on the buttons below the message
    *
    */  
    public function getCallBackQuery(): mixed
    {
        return $this->basicChatData['callback_query']['data'];
    }
    
    /**
     * SEND ANSWEARCALLBACKQUERY TO TELEGRAM WHEN THE INLINE BUTTON WAS PRESSED
     *
     * @param string $message
     * @return mixed
     * @throws JsonException
     */
    public function sendCallBackAnswer(string $message): mixed
    {
        $params = array('callback_query_id' => $this->basicChatData['callback_query']['id'],'text'=>$message);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/answerCallbackQuery",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * SENDING MESSAGE TO USER WHO PRESSED THE BUTTON
     *
     * @param string $message
     * @return mixed
     * @throws JsonException
     */
    public function sendMessageCallBack(string $message): mixed
    {
        $params = array('chat_id' => $this->basicChatData['callback_query']['from']['id'],'text'=>$message,);
        $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
    * SENDING INLINE KEYBOARD TO USER
    *
    * @param string $message - bot message
    * @param array $inlineKeyboard - keyboard
    * @return mixed - result of execution
    * @throws JsonException
    */   
    public function sendMessageWithInlineKeyboard(string $message, array $inlineKeyboard): mixed
    {
            $params = array('chat_id' => $this->basicChatData['message']['chat']['id'],'text'=>$message,'reply_markup' => json_encode(array('inline_keyboard'=>$inlineKeyboard)));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);

            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);     

    }

    /**
     * SENDING INLINE KEYBOARD TO SPECIAL USER
     *
     * @param string $chat_id
     * @param string $message
     * @param array $inlineKeyboard
     * @return mixed
     * @throws JsonException
     */
    public function sendMessageWithInlineKeyboardToUser(string $chat_id, string $message, array $inlineKeyboard): mixed
    {
            $params = array('chat_id' => $chat_id,'text'=>$message,'reply_markup' => array('inline_keyboard'=>$inlineKeyboard));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
    
    /**
    * SENDING BASE KEYBOARD TO USER
    *
    * @param string $message - bot message
    * @param array $keyboard - keyboard
    * @return mixed $result  - result of execution
    * @throws JsonException
    */
    
    public function sendMessageWithBaseKeyboard(string $message, array $keyboard): mixed
    {
            $params = array('chat_id' => $this->basicChatData['message']['chat']['id'],'text' => $message, 'reply_markup' => array('keyboard' => $keyboard, 'resize_keyboard' => true, 'is_persistent' => true));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
    /**
     * CallBack BaseKeyboard 
     *
     * @param string $message
     * @param array $keyboard
     * @return mixed
     * @throws JsonException
     */
    public function sendMessageWithBaseKeyboardCallBack(string $message, array $keyboard): mixed
    {
            $params = array('chat_id' => $this->basicChatData['callback_query']['from']['id'],'text' => $message, 'reply_markup' => array('keyboard' => $keyboard, 'resize_keyboard' => true, 'is_persistent' => true));
            $response = $this->request('POST', "https://api.telegram.org/bot$this->conftoken/sendMessage",['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}