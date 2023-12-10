<?php

namespace App\Keyboards;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;

class Keyboards
{

    public function __construct(
        private BotapiInterface $bot,
        private ConfigInterface $config,
    )
    {
    }


    public function start(): void
    {
        $startKeyboard = $this->config->get('keyboard.start');

        $this->bot->sendMessageWithBaseKeyboard('Whats up Man', $startKeyboard);
    }

    public function cancelAndStartHome(): void
    {
        $startKeyboard = $this->config->get('keyboard.start');

        $this->bot->sendMessageWithBaseKeyboardCallBack('🔴 Отменено!', $startKeyboard);
    }

    public function showGoBackKeyboard(): void
    {
        $goHomeKeyboard = $this->config->get('keyboard.goHome');

        $this->bot->sendMessageWithInlineKeyboard('😥 Пользователь не найден 😥', $goHomeKeyboard);
    }

    public function showKeyboardUserExist(): void
    {
        $createDealKeyboard = $this->config->get('keyboard.createDeal');

        $this->bot->sendMessageWithInlineKeyboard(
            " 💥Пользователь найден💥\n\n🎰 Выбирите действие: \n
        - Создать сделку\n        - Вернуть в главное меню\n
    ❗️ Нажимая \"Создать сделку\" у Вас есть 5 минут, чтобы описать условия
    ❗️ В противном случае придется начать заново",
            $createDealKeyboard
        );
    }

    public function sendConfirmationKeyboard(
        string $idSearchTable,
        string $id_telegram,
        string $lastSearchedUser,
        string $amount,
        string $currency,
        string $terms): void
    {
        $confirmDealKeyboard = $this->config->get('keyboard.confirmDeal');

        $this->bot->sendMessageWithInlineKeyboard(
            sprintf(
                "🧨 Подтверждение сделки № %s\n\n💎 Покупатель (user id): %s
💎 Продавец (user id): %s\n 🗯 Сумма сделки: %s %s\n  🔊 Предмет Сделки: %s",
                $idSearchTable,
                $id_telegram,
                $lastSearchedUser,
                $amount,
                $currency,
                $terms
            ),
            $confirmDealKeyboard
        );
    }

    public function showInvitationKeyboardToSeller(
        string $idSeller,
        string $idSearchTable,
        string $idBuyer,
        string $usernameBuyer,
        string $amount,
        string $currency,
        string $terms): void
    {
        $acceptDealKeyboard = $this->config->get('keyboard.acceptDeal');

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $idSeller,
            sprintf(
                "💥 Запрос на сделку № %s\n\n 🏆 Покупатель:\n       User ID - %s
       Username - %s\n\n 🔥 Cумма сделки: %s %s\n 🔊 Предмет Сделки: %s ",
                $idSearchTable,
                $idBuyer,
                $usernameBuyer,
                $amount,
                $currency,
                $terms
            ),
            $acceptDealKeyboard
        );
    }

    public function showBuyerThatSellerAcceptInvitationKeyboard(
        string $userWhichSendMeDeal,
        string $idOfDeal,
        string $amountofDeal,
        string $currencyfDeal,
        string $resultAmount,
        string $senderId,
        string $senderUsername,
        string $acceptortId,
        string $acceptorUsername,
        string $termsOfDeal,
        string $wallet,
    ): void
    {
        $isPaidKeyboard = $this->config->get('keyboard.isPaidMenu');

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $userWhichSendMeDeal,
            sprintf(
                "🚀 Пользователь принял сделку № %s\n
 💎 Сумма сделки: %s %s \n 🎖 С услугами гаранта: %s %s \n______________________________\n 💥 Покупатель:\n      - ID: %s\n      - Username: %s\n______________________________\n 💥 Продавец:\n      - ID: %s\n      - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s\n ______________________________\n🏦  Кошелек для оплаты:\n\n %s\n\n🗽 После оплаты нажмите \"Оплачено\"",
                $idOfDeal,
                $amountofDeal,
                $currencyfDeal,
                $resultAmount,
                $currencyfDeal,
                $senderId,
                $senderUsername,
                $acceptortId,
                $acceptorUsername,
                $termsOfDeal,
                $wallet
            ),
            $isPaidKeyboard
        );
    }

    public function sendToAdminChannelDataOfDeal(
        string $admin_chat_id,
        string $idOfDeal,
        string $amountofDeal,
        string $currencyofDeal,
        string $resultAmount,
        string $idBuyer,
        string $buyerUsername,
        string $idSeller,
        string $sellerUsername,
        string $termsOfDeal,
        string $wallet,
    ): void
    {

        $adminKeyboard = $this->config->get('keyboard.admin');

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $admin_chat_id,
            sprintf(
                "🚀 Создана Сделка № %s\n\n
 💎 Сумма сделки: %s %s\n 🎖 С услугами гаранта: %s %s\n______________________________\n 💥 Покупатель:\n - ID: %s\n - Username: %s\n______________________________\n 💥 Продавец:\n - ID: %s\n - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s\n ______________________________\n🏦 Кошелек пополнения:\n\n %s\n",
                $idOfDeal,
                $amountofDeal,
                $currencyofDeal,
                $resultAmount,
                $currencyofDeal,
                $idBuyer,
                $buyerUsername,
                $idSeller,
                $sellerUsername,
                $termsOfDeal,
                $wallet
            ),
            $adminKeyboard
        );
    }

    public function notifyBuyerAdminReceivedMoney(
        string $idBuyer,
        string $idOfDeal,
        string $amountofDeal,
        string $currency,
        string $buyerUsername,
        string $idSeller,
        string $sellerUsername,
        string $termsOfDeal,
        ): void
    {
        $isCompleteByBuyerKeyboard = $this->config->get('keyboard.buyerDealMenu');

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $idBuyer,
            sprintf(
                "🚀 Запущена Сделка № %s\n
 💎 Сумма сделки: %s %s\n______________________________\n 💥 Покупатель:\n - ID: %s\n - Username: %s\n______________________________\n 💥 Продавец:\n - ID: %s\n - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s",
                $idOfDeal,
                $amountofDeal,
                $currency,
                $idBuyer,
                $buyerUsername,
                $idSeller,
                $sellerUsername,
                $termsOfDeal,
            ),
            $isCompleteByBuyerKeyboard
        );
    }

    public function notifySellerAdminReceivedMoney(
        string $idSeller,
        string $idOfDeal,
        string $amountofDeal,
        string $currency,
        string $idBuyer,
        string $buyerUsername,
        string $sellerUsername,
        string $termsOfDeal): void
    {
        $isCompleteBySellerKeyboard = $this->config->get('keyboard.sellerDealMenu');

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $idSeller,
            sprintf(
                "🚀 Запущена Сделка № %s\n
 💎 Сумма сделки: %s %s\n______________________________\n 💥 Покупатель:\n - ID: %s\n - Username: %s\n______________________________\n 💥 Продавец:\n - ID: %s\n - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s",
                $idOfDeal,
                $amountofDeal,
                $currency,
                $idBuyer,
                $buyerUsername,
                $idSeller,
                $sellerUsername,
                $termsOfDeal,
            ),
            $isCompleteBySellerKeyboard
        );
    }
}