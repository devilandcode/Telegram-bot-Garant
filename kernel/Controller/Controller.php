<?php

namespace App\Kernel\Controller;

use App\Models\UserModel;

abstract class Controller
{

    public function __construct(
    )
    {
    }

    public function start(): void
    {
        $this->botKeyboard->start();
    }

    public function profile(): void
    {
        $this->botAnswer->sendMyProfileData(
            $this->parser->id_telegram,
            $this->parser->username,
            $this->cryptoApi->getBtcCurrency()->btcPrice,
            $this->cryptoApi->getEthCurrency()->ethPrice,
            $this->cryptoApi->getUSDTCurrency()->usdtPrice
        );
    }

    public function search(): void
    {
        $this->botAnswer->askUserIdToSearch();
    }

    public function getMyDeals(): void
    {
        $this->botAnswer->activeDeals();
    }

    public function support(): void
    {
        $this->botAnswer->explainHowToUseBot();
    }

    public function unknownCommand(): void
    {
        $this->botAnswer->unknownCommand();
    }
//
//    public function sendCallBackAnswerToTelegram(): void
//    {
//        $this->botApi->sendCallBackAnswer('');
//    }
//
//    public function checkNewUser(): int|string
//    {
//        $input = $this->botApi->getInputData();
//        $input = print_r($input, true);
//        file_put_contents('inputs.txt', $input, FILE_APPEND);
//        die;
//        if ($this->userDBManager->getUserInfoById($this->parser->id_telegram) == null &&
//            $this->parser->isChanel !== 'channel'
//        )
//        {
//
//            return $this->userDBManager->addNewUserToTable(
//                $this->parser->id_telegram,
//                $this->parser->username,
//                $this->parser->chat_id);
//        } else {
//            return 'Not New User';
//        }
//    }
//
//    /**
//     * The time difference from the last time a buyer was found.
//     * Used to set the time during which a deal can be opened.
//     * if more than 5(i set) minutes have passed, the transaction must be restarted
//     * @return mixed
//     */
//    protected function checkIsTimeOver(): void
//    {
//        $this->parser->parseLastSearchedData();
//        $time = $this->parser->getDiffTime();
//        file_put_contents('time.txt', $time . "\n", FILE_APPEND);
//        if ($time > 5) {
//            $this->botAnswer->showTimeIsOver();
//            die;
//        }
//    }
//
//    protected function getTransactionData(): array
//    {
//        $transationData = [];
//
//        $myDealDataArray = $this->userDBManager->getDataOfBuyer($this->parser->id_telegram);
//        $this->parser->parseTransactionData($myDealDataArray);
//        $transationData['buyer_username'] = ($this->userDBManager->getUserInfoById($this->parser->buyerId))['username'];
//        $transationData['seller_username'] = ($this->userDBManager->getUserInfoById($this->parser->sellerId))['username'];
//
//        if (str_contains($this->parser->amountOfDeal, "btc") !== false) {
//            $transationData['wallet'] = $this->config->get('bot.btc_wallet');
//            $amountWithComission = $this->parser->amountOfDeal * 1.08;
//            $transationData['result_amount'] = $amountWithComission . ' btc';
//            return $transationData;
//        } elseif (str_contains($this->parser->amountOfDeal, "usdt") !== false) {
//            $transationData['wallet'] = $this->config->get('bot.usdt_wallet');
//            $amountWithComission = $this->parser->amountOfDeal * 1.08;
//            $transationData['result_amount'] = $amountWithComission . ' usdt';
//            return $transationData;
//        } elseif (str_contains($this->parser->amountOfDeal, "eth") !== false) {
//            $transationData['wallet'] = $this->config->get('bot.eth_wallet');
//            $amountWithComission = $this->parser->amountOfDeal * 1.08;
//            $transationData['result_amount'] = $amountWithComission . ' eth';
//            return $transationData;
//        } else {
//            $this->botAnswer->uncorrectCurrency();
//            die;
//        }
//    }



}