<?php

namespace App\Kernel\HTTP;

interface BotapiInterface
{
    public function phpInput(): \stdClass;

    public function sendMessage(string $message): mixed;
    public function sendMessageToUser(string $chat_id, string $message): mixed;
    public function sendCallBackAnswer(string $message): mixed;
    public function sendMessageCallBack(string $message): mixed;
    public function sendMessageWithInlineKeyboard(string $message, array $inlineKeyboard): mixed;
    public function sendMessageWithInlineKeyboardToUser(string $chat_id, string $message, array $inlineKeyboard): mixed;
    public function sendMessageWithBaseKeyboard(string $message, array $keyboard): mixed;
    public function sendMessageWithBaseKeyboardCallBack(string $message, array $keyboard): mixed;

}