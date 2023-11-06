<?php

namespace App;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotApi;

class Keyboards
{
    private BotApi $bot;

    public function __construct(
        public ConfigInterface $config,
        public string $token)
    {
        $this->bot = new BotApi($token);
    }

    public function start(): void
    {
        $startKeyboard = $this->config->get('keyboard.start');

        $this->bot->sendMessageWithBaseKeyboard('Whats up Nigga', $startKeyboard);
    }

    public function cancelAndStartHome(): void
    {
        $startKeyboard = array(
            array(
                array('text' => '💀 Мой Профиль'),
                array('text' => '👀 Поиск Пользователя')
            ),
            array(
                array('text' => '🔥 Активные Сделки'),
                array('text' => '📪 Служба Поддержки')
            )
        );

        $this->bot->sendMessageWithBaseKeyboardCallBack('Whats up Nigga', $startKeyboard);
    }

    public function notExistSellerKeyboard(): void
    {
        $notExistKeyboard = array(
            array(
                array('text' => 'Главное Меню', 'callback_data' => 'cancel')
            )
        );

        $this->bot->sendMessageWithInlineKeyboard('😥 Пользователь не найден 😥', $notExistKeyboard);
    }

    public function existSellerKeyboard(): void
    {
        $existUserKeyboard = [
            [
                ['text' => 'Создать Сделку', 'callback_data' => 'startDeal'],
                ['text' => 'Отмена', 'callback_data' => 'cancel']
            ]
        ];

        $this->bot->sendMessageWithInlineKeyboard(
            " 💥Пользователь найден💥\n\n🎰 Выбирите действие: \n
        - Создать сделку\n        - Вернуть в главное меню\n
    ❗️ Нажимая \"Создать сделку\" у Вас есть 5 минут, чтобы описать условия
    ❗️ В противном случае придется начать заново",
            $existUserKeyboard
        );
    }

    public function sendConfirmationKeyboard(
        string $idSearchTable,
        string $id_telegram,
        string $lastSearchedUser,
        string $amount,
        string $terms): void
    {
        $keyboardConfirmAndSendDeal = array(
            array(
                array('text' => 'Подтвердить сделку', 'callback_data' => 'confirmAndSendDeal'),
                array('text' => 'Отмена', 'callback_data' => 'cancel')
            )
        );

        $this->bot->sendMessageWithInlineKeyboard(
            sprintf(
                "🧨 Подтверждение сделки № %s\n\n💎 Создал (user id): %s
💎 Направляет к (user id): %s\n 🗯 Сумма сделки: %s\n 🔊 Предмет Сделки: %s",
                $idSearchTable,
                $id_telegram,
                $lastSearchedUser,
                $amount,
                $terms
            ),
            $keyboardConfirmAndSendDeal
        );
    }

    public function acceptInvitationKeyboard(
        string $idSeller,
        string $idSearchTable,
        string $idBuyer,
        string $usernameBuyer,
        string $amount,
        string $terms): void
    {
        $acceptDealKeyboard = array(
            array(
                array('text' => 'Принять Сделку', 'callback_data' => 'acceptDeal'),
                array('text' => 'Отмена', 'callback_data' => 'cancelInvitationBySeller')
            )
        );

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $idSeller,
            sprintf(
                "💥 Вам направили запрос на сделку № %s\n\n 🏆 Покупатель:\n       User ID - %s
       Username - %s\n\n 🔥 Cумма сделки: %s\n 🔊 Предмет Сделки: %s ",
                $idSearchTable,
                $idBuyer,
                $usernameBuyer,
                $amount,
                $terms
            ),
            $acceptDealKeyboard
        );
    }

    public function notifyAboutAcceptionKeyboard(
        string $userWhichSendMeDeal,
        string $idOfDeal,
        string $amountofDeal,
        string $resultAmount,
        string $senderId,
        string $senderUsername,
        string $acceptortId,
        string $acceptorUsername,
        string $termsOfDeal,
        string $wallet,
    ): void
    {
        $acceptionKeyboard = array(
            array(
                array('text' => 'Оплачено', 'callback_data' => 'paid'),
                array('text' => 'Отмена', 'callback_data' => 'cancelDealByBuyer')
            )
        );

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $userWhichSendMeDeal,
            sprintf(
                "🚀 Пользователь принял сделку № %s\n
 💎 Сумма сделки: %s \n 🎖 Сумма сделки c 8 проц: %s \n       (Услуги Гаранта)\n______________________________\n 💥 Покупатель:\n      - ID: %s\n      - Username: %s\n______________________________\n 💥 Продавец:\n      - ID: %s\n      - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s\n ______________________________\n🏦  Кошелек для оплаты:\n\n %s\n\n🗽 После оплаты нажмите \"Оплачено\"",
                $idOfDeal,
                $amountofDeal,
                $resultAmount,
                $senderId,
                $senderUsername,
                $acceptortId,
                $acceptorUsername,
                $termsOfDeal,
                $wallet
            ),
            $acceptionKeyboard
        );
    }


    public function sendToAdminChannelDataOfDeal(
        string $admin_chat_id,
        string $idOfDeal,
        string $amountofDeal,
        string $resultAmount,
        string $idBuyer,
        string $buyerUsername,
        string $idSeller,
        string $sellerUsername,
        string $termsOfDeal,
        string $wallet,
    ): void
    {
        $adminKeyboard = array(
            array(
                array('text' => 'Взнос получен', 'callback_data' => $idOfDeal . 'adminAcceptMoney'),
                array('text' => 'Написать в сделку', 'callback_data' => $idOfDeal . 'sendMessageToDeal')
            ),
            array(
                array('text' => 'Написать в бот ', 'callback_data' => 'sendMessageToBot'),
                array('text' => 'Закрыть сделку', 'callback_data' => $idOfDeal . 'dealIsResolved')
            )
        );

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $admin_chat_id,
            sprintf(
                "🚀 Создана Сделка № %s\n\n
 💎 Сумма сделки: %s \n 🎖 Сумма сделки c проц: %s\n______________________________\n 💥 Покупатель:\n - ID: %s\n - Username: %s\n______________________________\n 💥 Продавец:\n - ID: %s\n - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s\n ______________________________\n🏦 Кошелек пополнения:\n\n %s\n",
                $idOfDeal,
                $amountofDeal,
                $resultAmount,
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

    public function notifyBuyerAdminRecievedMoney(
        string $idBuyer,
        string $idOfDeal,
        string $amountofDeal,
        string $buyerUsername,
        string $idSeller,
        string $sellerUsername,
        string $termsOfDeal,
        ): void
    {
        $buyersDealkeyboard = array(
            array(
                array('text' => 'Подтвердить', 'callback_data' => 'paid'),
                array('text' => 'Открыть спор', 'callback_data' => 'cancelDealByBuyer')
            )
        );

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $idBuyer,
            sprintf(
                "🚀 Запущена Сделка № %s\n
 💎 Сумма сделки: %s \n______________________________\n 💥 Покупатель:\n - ID: %s\n - Username: %s\n______________________________\n 💥 Продавец:\n - ID: %s\n - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s",
                $idOfDeal,
                $amountofDeal,
                $idBuyer,
                $buyerUsername,
                $idSeller,
                $sellerUsername,
                $termsOfDeal,
            ),
            $buyersDealkeyboard
        );
    }

    public function notifySellerAdminRecievedMoney(
        string $idSeller,
        string $idOfDeal,
        string $amountofDeal,
        string $idBuyer,
        string $buyerUsername,
        string $sellerUsername,
        string $termsOfDeal): void
    {
        $sellersDealkeyboard = array(
            array(
                array('text' => 'Выполнено', 'callback_data' => 'paid'),
                array('text' => 'Открыть спор', 'callback_data' => 'cancelDealByBuyer')
            )
        );

        $this->bot->sendMessageWithInlineKeyboardToUser(
            $idSeller,
            sprintf(
                "🚀 Запущена Сделка № %s\n
 💎 Сумма сделки: %s \n______________________________\n 💥 Покупатель:\n - ID: %s\n - Username: %s\n______________________________\n 💥 Продавец:\n - ID: %s\n - Username: %s\n ______________________________\n 📌 Предмет Сделки: %s",
                $idOfDeal,
                $amountofDeal,
                $idBuyer,
                $buyerUsername,
                $idSeller,
                $sellerUsername,
                $termsOfDeal,
            ),
            $sellersDealkeyboard
        );
    }
}










