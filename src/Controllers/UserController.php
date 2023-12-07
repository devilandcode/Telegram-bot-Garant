<?php

namespace App\Controllers;


use App\Services\UsersService\UserService;

class UserController
{
    public function __construct(
        private UserService $service
    )
    {
    }

    public function analyze(string $messageFromBot)
    {
        if ($this->service->isTelegramId($messageFromBot)) {
            $this->service->handleSellerId($messageFromBot);
            return;
        }

        /** Amount keywords are exist in config/amountKeywords.php */
        if ($this->service->hasAmoutKeywords($messageFromBot)) {
            $this->service->handleAmmoutOfDeal($messageFromBot);
            return;
        }


    }
//    public function checkIsUserExist(string $message): void
//    {
//        if ($this->userDBManager->getUserInfoById($message) !== null) {
//
//            $this->botKeyboard->showKeyboardUserExist();
//            $this->userDBManager->addToSearchTable($this->parser->id_telegram, $message);
//
//        } else {
//            $this->botKeyboard->showGoBackKeyboard();
//        }
//    }
//
//    public function askToEnterAmountOfDeal(): void
//    {
//        $this->botAnswer->askAmountOfDeal(
//            $this->cryptoApi->getBtcCurrency()->btcPrice,
//            $this->cryptoApi->getEthCurrency()->ethPrice,
//            $this->cryptoApi->getUSDTCurrency()->usdtPrice
//        );
//    }
//
//    public function setAmountOfDeal(string $message): void
//    {
//        $this->checkIsTimeOver();
//
//        if ($this->parser->parseLastSearchedData()) {
//            $idOfSearchTable = $this->parser->idSearchTable;
//            $this->userDBManager->addCryptoAmountToSeacrhTable($message, $idOfSearchTable);
//
//            $this->botAnswer->askTermsOfDeal();
//        }
//    }
//
//    public function setTermOfDeal(string $message)
//    {
//        $this->checkIsTimeOver();
//
//        if ($this->parser->parseLastSearchedData()) {
//            $idOfSearchTable = $this->parser->idSearchTable;
//            $this->userDBManager->addTermsOfDealToSearchTable($message, $idOfSearchTable);
//            $this->botKeyboard->sendConfirmationKeyboard(
//                $this->parser->idSearchTable,
//                $this->parser->idBuyer,
//                $this->parser->idSeller,
//                $this->parser->amount,
//                $message
//            );
//        }
//
//    }
//
//        public function sendToSellerInvitation(): void
//    {
//            $this->botKeyboard->acceptInvitationKeyboard(
//                $this->parser->idSeller,
//                $this->parser->idSearchTable,
//                $this->parser->idBuyer,
//                $this->userDBManager->getUserInfoById($this->parser->idBuyer)['username'],
//                $this->parser->amount,
//                $this->parser->terms
//            );
//
//            $this->botAnswer->notifyBuyerAboutSendingRequest();
//
//    }
//
//    public function notifyBuyerAboutAcceptionOfDeal(): void
//    {
//        $transactionData = $this->getTransactionData();
//
//        if (! empty($transactionData)) {
//            $this->botKeyboard->notifyAboutAcceptionKeyboard(
//                $this->parser->buyerId,
//                $this->parser->idOfDeal,
//                $this->parser->amountOfDeal,
//                $transactionData['result_amount'],
//                $this->parser->buyerId,
//                $transactionData['buyer_username'],
//                $this->parser->sellerId,
//                $transactionData['seller_username'],
//                $this->parser->termsOfDeal,
//                $transactionData['wallet']
//            );
//
//            $this->botKeyboard->sendToAdminChannelDataOfDeal(
//                $this->config->get('bot.admin_chat_id'),
//                $this->parser->idOfDeal,
//                $this->parser->amountOfDeal,
//                $transactionData['result_amount'],
//                $this->parser->buyerId,
//                $transactionData['buyer_username'],
//                $this->parser->sellerId,
//                $transactionData['seller_username'],
//                $this->parser->termsOfDeal,
//                $transactionData['wallet']
//            );
//        }
//
//        $this->botAnswer->waitingWhenBuyerWillPay();
//    }
//
//    public function showPaidByBuyer(): void
//    {
//        $this->botAnswer->notifyAdminDealIsPaid(
//            $this->config->get('bot.admin_chat_id'),
//            $this->parser->idSearchTable,
//            $this->parser->idBuyer
//        );
//
//        $this->botAnswer->checkingBuyersTranssaction();
//    }
//
//    public function cancelFillingDeal(): void
//    {
//        $this->botKeyboard->cancelAndStartHome();
//    }
//
//    public function declineInvitationBySeller(): void
//    {
//        $this->botAnswer->cancelInvitationBySeller();
//
//        $dealData = $this->userDBManager->getDataOfBuyer($this->parser->id_telegram);
//        $this->parser->parseData($dealData);
//
//        $this->botAnswer->notifyToBuyerInvitationWasCanceled(
//            $this->parser->idBuyer,
//            $this->parser->idSearchTable,
//            $this->parser->idSeller
//        );
//    }
//
//    public function declineDealByBuyer(): void
//    {
//        $this->botAnswer->cancelDealByBuyer();
//
//        $dealData = $this->userDBManager->getDataOfSeller($this->parser->id_telegram);
//        $this->parser->parseData($dealData);
//
//        $this->botAnswer->sendForAllThatBuyerCancelDeal(
//            $this->parser->idSeller,
//            $this->config->get('bot.admin_chat_id'),
//            $this->parser->idSearchTable,
//            $this->parser->idBuyer,
//        );
//    }
}