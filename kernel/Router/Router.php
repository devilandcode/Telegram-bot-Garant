<?php

namespace App\Kernel\Router;

use App\Controllers\CallBackQueryController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Middlewares\MiddlewareInterface;

class Router
{

    public function __construct(
        private \stdClass $phpInput,
        private MiddlewareInterface $isUsernameExist,
        private MiddlewareInterface $isNewUser,
        private ConfigInterface $config,
        private HomeController $homeController,
        private UserController $userController,
        private CallBackQueryController $callBackQueryController,
    )
    {
    }

    public function dispatch(): void
    {
        $input = $this->phpInput;
        $input = print_r($input, true);
        file_put_contents('inputs.txt', $input, FILE_APPEND);

        if ($this->isTextMessageFromBot()) {
            $this->dispatchTextMessageFromBot();
            return;
        }

        if ($this->isCallBackQueryFromBot()) {
            $this->dispatchCallBackQueryFromBot();
            return;
        }

        if ($this->isTextMessageFromAdminChannel()) {
            $this->dispatchTextMessageFromAdminChannel();
        }
    }

    private function dispatchTextMessageFromBot()
    {
        $this->goUsernameMiddleware();
        $this->goNewUserMiddleware();

        match ($this->phpInput->message->text) {
            $this->config->get('messages.start') => call_user_func([$this->homeController, 'start']),
            $this->config->get('messages.profile') => call_user_func([$this->homeController, 'profile']),
            $this->config->get('messages.userSearch') => call_user_func([$this->homeController, 'search']),
            $this->config->get('messages.activeDeals') => call_user_func([$this->homeController, 'getMyDeals']),
            $this->config->get('messages.supportService') => call_user_func([$this->homeController, 'support']),
            default => call_user_func(
                [$this->userController, 'analyze'],
                $this->phpInput->message->text
            ),
        };
    }

    private function dispatchTextMessageFromAdminChannel()
    {

    }

    private function dispatchCallBackQueryFromBot()
    {
        /** Reply to Telegram that the callbackQuery has been received by us.*/
        call_user_func([$this->callBackQueryController,'sendCallBackAnswerToTelegram']);

        match ($this->phpInput->callback_query->data) {
            $this->config->get('tg_callbacks.start') => call_user_func([$this->callBackQueryController, 'askToEnterAmountOfDeal']),
            $this->config->get('tg_callbacks.cancelStart') => call_user_func([$this->callBackQueryController, 'cancelStartDeal']),
            $this->config->get('tg_callbacks.confirm') => call_user_func([$this->callBackQueryController, 'sendToSellerInvitation']),
            $this->config->get('tg_callbacks.cancelConfirm') => call_user_func([$this->callBackQueryController, 'cancelConfirmDeal']),
            $this->config->get('tg_callbacks.accept') => call_user_func([$this->callBackQueryController, 'acceptInvitationFromBuyer']),
            $this->config->get('tg_callbacks.cancelInvite') => call_user_func([$this->callBackQueryController, 'cancelInvitationFromBuyer']),
            $this->config->get('tg_callbacks.paid') => call_user_func([$this->callBackQueryController, 'sendToAdminThatBuyerPaidToEscrow']),
            $this->config->get('tg_callbacks.refused_to_pay') => call_user_func([$this->callBackQueryController, 'showAdminAndSellerThatBuyerRefusedToPay']),
            $this->config->get('tg_callbacks.money_received') => call_user_func([$this->callBackQueryController, 'showBuyerAndSellerThatAdminGotTheMoney']),
        };

    }

    private function isTextMessageFromBot(): bool
    {
        return isset($this->phpInput->message->text) ? true : false;
    }

    private function isTextMessageFromAdminChannel(): bool
    {
        return isset($this->phpInput->channel_post->text) ? true : false;
    }

    private function isCallBackQueryFromBot(): bool
    {
        return isset($this->phpInput->callback_query->data) ? true : false;
    }

    private function goUsernameMiddleware()
    {
        $phpInput = $this->phpInput;

        $this->isUsernameExist->check(compact('phpInput'));
    }

    private function goNewUserMiddleware()
    {
        $idTelegram = $this->phpInput->message->from->id;
        $username   = $this->phpInput->message->from->username;
        $idChat     = $this->phpInput->message->chat->id;


        $this->isNewUser->check(compact('idTelegram', 'username', 'idChat'));
    }

}
