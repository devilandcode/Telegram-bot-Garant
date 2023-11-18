<?php

namespace App\Kernel\Router;

use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotApi;
use App\Messages;


class Router
{
    private string $botCallBackQuery;

    public function __construct(
        private BotApi          $botApi,
        private ConfigInterface $config,
        private UserController  $userController,
        private AdminController $adminController,
        private Messages        $botAnswer,
    )
    {
        $this->initBotMessage();
    }

    public function dispatch(string $message)
    {

        if ($this->botCallBackQuery !== 'NotSet') {
            $this->dispatchCallbackQuery($this->botCallBackQuery);
        }
        if (is_numeric($message) && strlen($message) === 10) {
            $this->dispatchBotNumericMessage($message);
        } elseif($this->isKeyWordsExist($message) !== 'not_exist') {
            $this->dispatchBotMessageWithKeywords($message);
        } else {
        $this->dispatchBotMessage($message);
    }
    }

    public function dispatchBotMessage(string $message): void
    {
        match ($message) {
            $this->config->get('messages.start') => call_user_func([$this->userController, 'start']),
            $this->config->get('messages.profile') => call_user_func([$this->userController, 'profile']),
            $this->config->get('messages.userSearch') => call_user_func([$this->userController, 'search']),
            $this->config->get('messages.activeDeals') => call_user_func([$this->userController, 'getMyDeals']),
            $this->config->get('messages.supportService') => call_user_func([$this->userController, 'support']),
            $this->config->get('messages.message_wrong') => file_put_contents('m.txt', $message, FILE_APPEND),
            default => call_user_func([$this->userController, 'unknownCommand']),
        };
    }

    public function dispatchBotMessageWithKeywords($message): void
    {
        match ($this->isKeyWordsExist($message)) {
            $this->config->get('messages.keyword_crypto') => call_user_func([$this->userController, 'setAmountOfDeal'], $message),
            $this->config->get('messages.keyword_deal') => call_user_func([$this->userController, 'setTermOfDeal'], $message),
            default => call_user_func([$this->botAnswer, 'uncorrectCurrency']),
        };
    }

    public function dispatchBotNumericMessage(string $message): void
    {
        call_user_func([$this->userController, 'checkIsUserExist'], $message);

    }

    public function dispatchCallbackQuery(string $callBackQuery): void
    {
        call_user_func([$this->userController,'sendCallBackAnswerToTelegram']);

        match ($callBackQuery) {
            $this->config->get('tg_callbacks.start') => call_user_func([$this->userController, 'askToEnterAmountOfDeal']),
            $this->config->get('tg_callbacks.confirm') => call_user_func([$this->userController, 'sendToSellerInvitation']),
            $this->config->get('tg_callbacks.accept') => call_user_func([$this->userController, 'notifyBuyerAboutAcceptionOfDeal']),
            $this->config->get('tg_callbacks.paid') => call_user_func([$this->userController, 'showPaidByBuyer']),
            $this->config->get('tg_callbacks.money_received') => call_user_func([$this->adminController, 'startByAdmin']),
        };
    }

    public function initBotMessage(): void
    {
        $this->botCallBackQuery = $this->botApi->getCallBackQuery();
    }

    private function isKeyWordsExist(string $message): string
    {
        $cryptoWords = $this->config->getArray('crypto_keywords');
        foreach ($cryptoWords as $word) {
            if (str_contains($message, $word)) {
                return 'crypto';
            }
        }
        $dealWords = $this->config->getArray('deal_words');
        foreach ($dealWords as $word) {
            if (str_contains($message, $word)) {
                return 'deal';
            }
        }
        return 'not_exist';
    }
}
