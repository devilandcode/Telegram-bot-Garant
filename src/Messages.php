<?php

namespace App;

use JsonException;

class Messages
{
    private Api $bot;

    public function __construct($token)
    {
        $this->bot = new Api($token);
    }

    /**
     * Send answer if user texted unknown command
     * @return void
     * @throws JsonException
     */
    public function unknownCommand(): void
    {
        $this->bot->sendMessage('😥 Неизвестная команда');
    }

    /**
     * Send Message with my profile's data
     * @param string $id_telegram
     * @param string $username
     * @param string $btcPrice
     * @param string $ethPrice
     * @return void
     * @throws JsonException
     */
    public function sendMyProfileData(string $id_telegram, string $username, string $btcPrice, string $ethPrice): void
    {
        $this->bot->sendMessage(
            sprintf(
                "Мой Профиль\r\n\r\n🚀 Telegram ID: %s\r\n💀 Username: %s\r\n🔥 Количество сделок: 0\n\n📈 Курс BTC  %s USD\n📉 Курс ETH   %s  USD",
                $id_telegram,
                $username,
                $btcPrice,
                $ethPrice
            )
        );
    }

    /**
     * My active Deals
     * @return void
     * @throws JsonException
     */
    public function activeDeals(): void
    {
        $this->bot->sendMessage(
            sprintf(
                "🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥\n\n 🚀 Количество активных сделок: %s", 0
            )
        );
    }

    /**
     * Send an instruction - how to use the bot
     * @return void
     * @throws JsonException
     */
    public function explainHowToUseBot(): void
    {
        $this->bot->sendMessage(
"🚀 Краткая инструкция:\n
1 - Пригласите продавца в бот
2 - Найдите его в меню поиска по User ID
3 - Следуя инструкция введите сумму и условия сделки
4 - Подтвердите и отправьте запрос продавцу
5 - После подтверждения вам будет выдан кошелек
6 - После успешного пополнения сделка запуститься

После запуска сделка у вас будут следущие опции:
- Подтвердить выполнение
- Открыть спор
- Связаться с поддержкой"
        );
    }

    /**
     * Ask user to enter the amount of deal
     * @param string $btcPrice
     * @param string $ethPrice
     * @return mixed
     * @throws JsonException
     */
    public function askAmountOfDeal(string $btcPrice, string $ethPrice): mixed
    {
        return $this->bot->sendMessageCallBack(
            sprintf(
                "🔥Введите сумму сделки в криптовалюте\n\n📈 Курс BTC  %s USD\n📉 Курс ETH   %s  USD\n\nНазвание крипты слитно без пробела\n(Пример 0.00235btc)",
                $btcPrice,
                $ethPrice
            )
        );
    }

    /**
     * Ask user to enter the terms of deal
     * @return void
     * @throws JsonException
     */
    public function askTermsOfDeal(): void
    {
        $this->bot->sendMessage(
            "🎲 Опишите предмет сделки\n❗️ Лаконично, четко, по существу\n
💲 Вначале описания напишите - !Сделка
(Пример: !Сделка о продаже парсера, который .....)"
        );
    }

    /**
     * notify the sender that the request has been sent
     * @return void
     * @throws JsonException
     */
    public function notifyBuyerAboutSendingRequest(): void
    {
        $this->bot->sendMessageCallBack('Запрос отправлен, ожидаем подтверждения');
    }

    /**
     * Ask User to input telegram user id to search it
     * @return void
     * @throws JsonException
     */
    public function askIdToSearchUser(): void
    {
        $this->bot->sendMessage(
            "🎯 Для поиска пользователя:\r\n\r\n 📌  Напишите его User ID:\r\n  💎 Число из 10 цифр \r\n 🧩  Пример - 6648127615\r\n  (Находится там же, где и username, только ниже)"
        );
    }

    /**
     * Notify user which accepted the deal that we wait when creator of deal send money to garant's wallet
     * @return void
     * @throws JsonException
     */
    public function waitingWhenBuyerWillPay()
    {
        $this->bot->sendMessageCallBack("🕯 Ожидаем оплаты от пользователя");
    }

    public function checkingBuyersTranssaction()
    {
        $this->bot->sendMessageCallBack("👀 чекаем вашу транзакцию - ожидайте");
    }

    public function cancelDealByBuyer()
    {
        $this->bot->sendMessageCallBack("❗️ Сделка отменена");
    }

    public function cancelInvitationBySeller()
    {
        $this->bot->sendMessageCallBack("❗️ Сделка отклонена");
    }

    public function notifyToBuyerInvitationWasCanceled(
        string $idBuyer,
        string $idSearchTable,
        string $idSeller
    )
    {

        $this->bot->sendMessageToUser($idBuyer,
            sprintf("❌️ Запрос на сделку № %s\n Отклонен продавцом User ID - %s", $idSearchTable, $idSeller)
        );
    }

    public function sendForAllThatBuyerCancelDeal(
        string $idSeller,
        string $admin_chat_id,
        string $idSearchTable,
        string $idBuyer
    ): void
    {
        $this->bot->sendMessageToUser($idSeller,
        sprintf("❌️ Сделка № %s\n Отменена покупателем User ID - %s", $idSearchTable, $idBuyer)
        );

        $this->bot->sendMessageToUser($admin_chat_id,
            sprintf("❌️ Сделка № %s\n Отменена покупателем User ID - %s", $idSearchTable, $idBuyer)
        );
    }

    public function notifyAdminDealIsPaid(
        string $admin_chat_id,
        string $idSearchTable,
        string $idBuyer
    ): void
    {
        $this->bot->sendMessageToUser($admin_chat_id,
            sprintf("✅ Сделка № %s Оплачена\n Покупателем User ID - %s ", $idSearchTable, $idBuyer)
        );
    }

    public function notifyAdminDealConfirmed(string $admin_chat_id, string $idOfDeal)
    {
        $this->bot->sendMessageToUser($admin_chat_id, sprintf("🔥 Сделка № %s - Запущена, Оплата получена", $idOfDeal));
    }

    public function notifyAdminDealResolved(string $admin_chat_id, string $idOfDeal)
    {
        $this->bot->sendMessageToUser($admin_chat_id, sprintf("💚 Сделка № %s Успешно Завершена", $idOfDeal));
    }

    public function notifyBuyerDealResolved(string $buyerId, string $idOfDeal)
    {
        $this->bot->sendMessageToUser($buyerId, sprintf("💚 Сделка № %s Успешно Завершена", $idOfDeal));
    }

    public function notifySellerDealResolved(string $sellerId, string $idOfDeal)
    {
        $this->bot->sendMessageToUser($sellerId, sprintf("💚 Сделка № %s Успешно Завершена", $idOfDeal));
    }

    public function askAdminToTextHisMessageToBot(string $admin_chat_id)
    {
        $this->bot->sendMessageToUser($admin_chat_id,
            "🖍 Написать сообщение в бот\n       Начните со слова \"bot:\"
      (Пример - bot: .....Ваше сообщение)");
    }

    public function mailToBot(string $id_telegram, string $messageFromBot): mixed
    {
        return $this->bot->sendMessageToUser($id_telegram, $messageFromBot);
    }
}
