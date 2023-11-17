<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;

class UserController extends Controller
{
    public function checkIsUserExist(string $message): void
    {
        if ($this->userDBManager->getUserInfoById($message) !== null) {

            $this->botKeyboard->showKeyboardUserExist();
            $this->userDBManager->addToSearchTable($this->parser->id_telegram, $message);

        } else {
            $this->botKeyboard->showGoBackKeyboard();
        }
    }

    public function askToEnterAmountOfDeal(): void
    {
        $this->botAnswer->askAmountOfDeal(
            $this->cryptoApi->getBtcCurrency()->btcPrice,
            $this->cryptoApi->getEthCurrency()->ethPrice,
            $this->cryptoApi->getUSDTCurrency()->usdtPrice
        );
    }

    public function setAmountOfDeal(string $message): void
    {
        $time = $this->checkDifferenceTime();

        if ($this->parser->parseLastSearchedData()) {
            $idOfSearchTable = $this->parser->idSearchTable;
            $this->userDBManager->addCryptoAmountToSeacrhTable($message, $idOfSearchTable);
            $this->botAnswer->askTermsOfDeal();
        }
    }

    public function setTermOfDeal(string $message)
    {
        $this->checkDifferenceTime();

        if ($this->parser->parseLastSearchedData()) {
            $idOfSearchTable = $this->parser->idSearchTable;
            $this->userDBManager->addTermsOfDealToSearchTable($message, $idOfSearchTable);
            $this->botKeyboard->sendConfirmationKeyboard(
                $this->parser->idSearchTable,
                $this->parser->idBuyer,
                $this->parser->idSeller,
                $this->parser->amount,
                $message
            );
        }

    }

        public function sendToSellerInvitation(): void
    {
            $this->botKeyboard->acceptInvitationKeyboard(
                $this->parser->idSeller,
                $this->parser->idSearchTable,
                $this->parser->idBuyer,
                $this->userDBManager->getUserInfoById($this->parser->idBuyer)['username'],
                $this->parser->amount,
                $this->parser->terms
            );

            $this->botAnswer->notifyBuyerAboutSendingRequest();

    }

    public function notifyBuyerAboutAcceptionOfDeal(): void
    {
        $myDealDataArray = $this->userDBManager->getDataOfBuyer($this->parser->id_telegram);
        $this->parser->parseDealData($myDealDataArray);
        $buyerUsername = ($this->userDBManager->getUserInfoById($this->parser->buyerId))['username'];
        $sellerUsername = ($this->userDBManager->getUserInfoById($this->parser->sellerId))['username'];
        if (str_contains($this->parser->amountOfDeal, "btc") !== false) {
            $wallet = 'walletBTC';
            $amountWithComission = $this->parser->amountOfDeal * 1.08;
            $resultAmount = $amountWithComission . ' btc';
        } else {
            $wallet = 'walletETH';
            $amountWithComission = $this->parser->amountOfDeal * 1.08;
            $resultAmount = $amountWithComission . ' eth';
        }
        if (isset($myDealDataArray)) {
            $this->botKeyboard->notifyAboutAcceptionKeyboard(
                $this->parser->buyerId,
                $this->parser->idOfDeal,
                $this->parser->amountOfDeal,
                $resultAmount,
                $this->parser->buyerId,
                $buyerUsername,
                $this->parser->sellerId,
                $sellerUsername,
                $this->parser->termsOfDeal,
                $wallet
            );

//            $this->botKeyboard->sendToAdminChannelDataOfDeal(
//                's',
//                $this->parser->idOfDeal,
//                $this->parser->amountOfDeal,
//                $resultAmount,
//                $this->parser->buyerId,
//                $buyerUsername,
//                $this->parser->sellerId,
//                $sellerUsername,
//                $this->parser->termsOfDeal,
//                $wallet
//            );
        }

        $this->botAnswer->waitingWhenBuyerWillPay();
    }


}