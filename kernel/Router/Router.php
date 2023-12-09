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
//            $this->config->get('tg_callbacks.accept') => call_user_func([$this->userController, 'notifyBuyerAboutAcceptionOfDeal']),
//            $this->config->get('tg_callbacks.paid') => call_user_func([$this->userController, 'showPaidByBuyer']),
//            $this->config->get('tg_callbacks.dont_send_deal') => call_user_func([$this->userController, 'cancelFillingDeal']),
//            $this->config->get('tg_callbacks.decline_invitation') => call_user_func([$this->userController, 'declineInvitationBySeller']),
//            $this->config->get('tg_callbacks.cancel_by_buyer') => call_user_func([$this->userController, 'declineDealByBuyer']),
//            $this->config->get('tg_callbacks.money_received') => call_user_func([$this->adminController, 'startByAdmin']),
//            $this->config->get('tg_callbacks.deal_complete') => call_user_func([$this->adminController, 'markDealComplete']),
//            $this->config->get('tg_callbacks.bulk_mailing') => call_user_func([$this->adminController, 'askWhatMessageToSend']),
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

//
//    public function dispatchBotMessage(string $message): void
//    {
//        match ($message) {
//            $this->config->get('messages.start') => call_user_func([$this->userController, 'start']),
//            $this->config->get('messages.profile') => call_user_func([$this->userController, 'profile']),
//            $this->config->get('messages.userSearch') => call_user_func([$this->userController, 'search']),
//            $this->config->get('messages.activeDeals') => call_user_func([$this->userController, 'getMyDeals']),
//            $this->config->get('messages.supportService') => call_user_func([$this->userController, 'support']),
//            $this->config->get('messages.message_wrong') => file_put_contents('m.txt', $message, FILE_APPEND),
//            default => call_user_func([$this->userController, 'unknownCommand']),
//        };
//    }
//
//    public function dispatchBotMessageWithKeywords($message): void
//    {
//        match ($this->isKeyWordsExist($message)) {
//            $this->config->get('messages.keyword_crypto') => call_user_func([$this->userController, 'setAmountOfDeal'], $message),
//            $this->config->get('messages.keyword_deal') => call_user_func([$this->userController, 'setTermOfDeal'], $message),
//            default => call_user_func([$this->botAnswer, 'uncorrectCurrency']),
//        };
//    }
//
//    public function dispatchBotNumericMessage(string $message): void
//    {
//        call_user_func([$this->userController, 'checkIsUserExist'], $message);
//
//    }
//
//    public function dispatchCallbackQuery(string $callBackQuery): void
//    {
//        call_user_func([$this->userController,'sendCallBackAnswerToTelegram']);
//
//        match ($callBackQuery) {
//            $this->config->get('tg_callbacks.start') => call_user_func([$this->userController, 'askToEnterAmountOfDeal']),
//            $this->config->get('tg_callbacks.confirm') => call_user_func([$this->userController, 'sendToSellerInvitation']),
//            $this->config->get('tg_callbacks.accept') => call_user_func([$this->userController, 'notifyBuyerAboutAcceptionOfDeal']),
//            $this->config->get('tg_callbacks.paid') => call_user_func([$this->userController, 'showPaidByBuyer']),
//            $this->config->get('tg_callbacks.dont_send_deal') => call_user_func([$this->userController, 'cancelFillingDeal']),
//            $this->config->get('tg_callbacks.decline_invitation') => call_user_func([$this->userController, 'declineInvitationBySeller']),
//            $this->config->get('tg_callbacks.cancel_by_buyer') => call_user_func([$this->userController, 'declineDealByBuyer']),
//            $this->config->get('tg_callbacks.money_received') => call_user_func([$this->adminController, 'startByAdmin']),
//            $this->config->get('tg_callbacks.deal_complete') => call_user_func([$this->adminController, 'markDealComplete']),
//            $this->config->get('tg_callbacks.bulk_mailing') => call_user_func([$this->adminController, 'askWhatMessageToSend']),
//        };
//    }
//
//    public function dispathAdminMessage(string $messageFromAdmin): void
//    {
//        match ($this->isKeyWordsExist($messageFromAdmin)) {
//            $this->config->get('messages.keyword_admin') => call_user_func([$this->adminController, 'makeBulkMailing'], $messageFromAdmin),
//            default => call_user_func([$this->botAnswer, 'unknownCommand']),
//        };
//    }
//
//    public function initBotMessage(): void
//    {
//        $this->botCallBackQuery = $this->botApi->getCallBackQuery();
//    }
//
//    private function isKeyWordsExist(string $message): string
//    {
//        $cryptoWords = $this->config->getArray('crypto_keywords');
//        foreach ($cryptoWords as $word) {
//            if (str_contains($message, $word)) {
//                return 'crypto';
//            }
//        }
//        $dealWords = $this->config->getArray('deal_keywords');
//        foreach ($dealWords as $word) {
//            if (str_contains($message, $word)) {
//                return 'deal';
//            }
//        }
//
//        $adminWords = $this->config->getArray('admin_keywords');
//        foreach ($adminWords as $word) {
//            if (str_contains($message, $word)) {
//                 return 'admin';
//            }
//        }
//        return 'not_exist';
//    }
}
