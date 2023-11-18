<?php

namespace App\Kernel;

use App\Kernel\Container\Container;

class Bot
{
    private Container $container;

    public string $message;


    public function __construct(
    )
    {
        $this->container = new Container();

    }

    public function run(): void
    {
        $this->container
            ->router
            ->dispatch(
                $this->container->botApi->getMessage(),
                $this->container->botApi->getMessageFromAdminChannel()
            );
    }

//
//    /**
//     * Check is user exist in bot's table
//     * If not -> return null
//     * @param string $messageFromBot
//     * @return array|null
//     */
//    public function checkIsUserExist(string $messageFromBot): ?array
//    {
//        $idSellerToSearch = $messageFromBot;
//        return $searchedArray = $this->userManager->getUserInfoById($idSellerToSearch);
//    }
//
//    /**
//     * Parse last request data (user which searched, time, amount(if entered), terms(if entered)
//     * @return void
//     */
//    public function getDifferenceTime(): mixed
//    {
//        $lastSearchedDataArray = $this->userManager->getDataOfSeller($this->parser->id_telegram);
//        $this->parser->parseLastSearchedData($lastSearchedDataArray);
//        return $this->parser->getDiffTime();
//    }
//
//    /**
//     * If user has started the deal(pressed inline button "startDeal") -> ask him to enter an amount of deal
//     * @return void
//     * @throws JsonException
//     */
//    public function askToEnterAmountOfDeal(): void
//    {
//        $btcPrice = $this->crypto->getBtcCurrency()->btcPrice;
//        $ethPrice = $this->crypto->getEthCurrency()->ethPrice;
//
//        $this->messages->askAmountOfDeal($btcPrice, $ethPrice);
//    }
//
//    /**
//     * Confirm all terms of deal and send invitation to user or cancel the deal
//     * @return void
//     */
//    public function confrimTermsAndSendDeal(): void
//    {
//        $lastSearchedDataArray = $this->userManager->getDataOfSeller($this->parser->id_telegram);
//        $this->parser->parseLastSearchedData($lastSearchedDataArray);
//
//        $this->keyboards->sendConfirmationKeyboard(
//            $this->parser->idSearchTable,
//            $this->parser->id_telegram,
//            $this->parser->idSeller,
//            $this->parser->amount,
//            $this->parser->terms
//        );
//    }
//

//
//    /**
//     * Notify sender of acception of deal and show him which crypto wallet he must to top up
//     * Send to admin channel data of deal
//     * @param string $walletBTC
//     * @param string $walletETH
//     * @return void
//     */
//
//    /**
//     * Send to admin channel notification of new deal
//     * @param string $admin_chat_id
//     * @return void
//     */
//    public function notifyAdminDealIsPaid(string $admin_chat_id)
//    {
//        $lastSearchedDataArray = $this->userManager->getDataOfSeller($this->parser->parseInputInfo()->id_telegram);
//        $this->parser->parseLastSearchedData($lastSearchedDataArray);
//
//        $this->messages->notifyAdminDealIsPaid(
//            $admin_chat_id,
//            $this->parser->idSearchTable,
//            $this->parser->idBuyer
//        );
//    }
//
//    public function notifyBuyerThatSellerCancelInviation()
//    {
//        $lastSearchedDataArray = $this->userManager->getDataOfBuyer($this->parser->id_telegram);
//        $this->parser->parseLastSearchedData($lastSearchedDataArray);
//
//        $this->messages->notifyToBuyerInvitationWasCanceled(
//            $this->parser->idBuyer,
//            $this->parser->idSearchTable,
//            $this->parser->idSeller
//        );
//    }
//
//    /**
//     * Send to buyer,seller and admin channel that the Buyer has canceled the deal
//     * @param string $admin_chat_id
//     * @return void
//     */
//    public function notifyAllThatBuyerCancelDeal(string $admin_chat_id)
//    {
//        $lastSearchedDataArray = $this->userManager->getDataOfSeller($this->parser->id_telegram);
//        $this->parser->parseLastSearchedData($lastSearchedDataArray);
//
//        $this->messages->sendForAllThatBuyerCancelDeal(
//            $this->parser->idSeller,
//            $admin_chat_id,
//            $this->parser->idSearchTable,
//            $this->parser->idBuyer
//        );
//    }
//
//    /**
//     * When Admin pressed "Transaction has been received" -> notify all and start deal
//     * @param string $admin_chat_id
//     * @param string $callBackQuery
//     * @return void
//     */
//    public function confirmAndStartDeal(string $admin_chat_id, string $callBackQuery)
//    {
//        $idOfDeal = (int)$callBackQuery;
//        $idOfDeal = (string)$idOfDeal;
//        $dealDataArray = $this->userManager->getDataOfDeal($idOfDeal);
//        $this->parser->parseDealData($dealDataArray);
//
//        $this->keyboards->notifyBuyerAdminRecievedMoney(
//            $this->parser->buyerId,
//            $this->parser->idOfDeal,
//            $this->parser->amountOfDeal,
//            $this->userManager->getUserInfoById($this->parser->buyerId)['username'],
//            $this->parser->sellerId,
//            $this->userManager->getUserInfoById($this->parser->sellerId)['username'],
//            $this->parser->termsOfDeal
//        );
//
//        $this->keyboards->notifySellerAdminRecievedMoney(
//            $this->parser->sellerId,
//            $this->parser->idOfDeal,
//            $this->parser->amountOfDeal,
//            $this->parser->buyerId,
//            $this->userManager->getUserInfoById($this->parser->buyerId)['username'],
//            $this->userManager->getUserInfoById($this->parser->sellerId)['username'],
//            $this->parser->termsOfDeal
//        );
//
//        $this->dealManager->addDataToConfirmedDealTable(
//            $this->parser->idOfDeal,
//            $this->parser->buyerId,
//            $this->parser->sellerId,
//            $this->parser->amountOfDeal,
//            $this->parser->termsOfDeal
//        );
//
//        $this->messages->notifyAdminDealConfirmed($admin_chat_id, $this->parser->idOfDeal);
//
//    }
//
//    public function markDealAsResolved(string $admin_chat_id, string $callBackQuery)
//    {
//        $idOfDeal = (int)$callBackQuery;
//        $idOfDeal = (string)$idOfDeal;
//        $dealDataArray = $this->userManager->getDataOfDeal($idOfDeal);
//        $this->parser->parseDealData($dealDataArray);
//
//        $this->messages->notifyAdminDealResolved($admin_chat_id,$this->parser->idOfDeal);
//        $this->messages->notifyBuyerDealResolved($this->parser->buyerId,$this->parser->idOfDeal);
//        $this->messages->notifySellerDealResolved($this->parser->sellerId,$this->parser->idOfDeal);
//
//        $this->dealManager->markDealIsREsolved($idOfDeal);
//    }
//
//    public function askAdminToTextHisMessageToBot(string $admin_chat_id)
//    {
//        $this->messages->mailBulkToBot($admin_chat_id);
//    }
//
//    public function mailBulkToBot()
//    {
//        $allUsersIdArray = $this->userManager->getAllUsersID();
//        $messageFromAdmin = trim($this->bot->getMessageFromAdminChannel());
//        $messageToBot = mb_substr($messageFromAdmin, 4);
//        foreach($allUsersIdArray as $key => $value) {
//            try {
//                $this->messages->mailToBot($value['id_telegram'], $messageToBot);
//            } catch (Exception $e) {
//                $errorData = $e->getMessage() ??'Not $e';
//            }
//            file_put_contents('response.txt', $errorData . "\n", FILE_APPEND);
//        }
//    }
//    private function setProperties(): void
//    {
//        $this->crypto = $this->container->crypto;
//        $this->botMessage = $this->container->messages;
//        $this->botKeyboard = $this->container->keyboards;
//        $this->parser = $this->container->parser;
//        $this->userManager = $this->container->userManager;
//    }


}

