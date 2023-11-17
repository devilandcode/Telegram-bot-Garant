<?php

namespace App\Kernel\Controller;

use App\Kernel\HTTP\BotApi;
use App\Kernel\HTTP\CryptoApi;
use App\Kernel\Parser\ParserUserData;
use App\Keyboards;
use App\Messages;
use App\Models\UserModel;

abstract class Controller
{

    public function __construct(
        protected BotApi $botApi,
        protected Keyboards $botKeyboard,
        protected Messages $botAnswer,
        protected CryptoApi $cryptoApi,
        protected UserModel $userDBManager,
        protected ParserUserData $parser,
    )
    {
        $this->checkNewUser();
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

    public function sendCallBackAnswerToTelegram(): void
    {
        $this->botApi->sendCallBackAnswer('');
    }

    public function checkNewUser(): int|string
    {
        if ($this->userDBManager->getUserInfoById($this->parser->id_telegram) == null)
        {

            return $this->userDBManager->addNewUserToTable(
                $this->parser->id_telegram,
                $this->parser->username,
                $this->parser->chat_id);
        } else {
            return 'Not New User';
        }
    }

    /**
     * The time difference from the last time a buyer was found.
     * Used to set the time during which a deal can be opened.
     * if more than 5(i set) minutes have passed, the transaction must be restarted
     * @return mixed
     */
    public function checkDifferenceTime(): mixed
    {
        $this->parser->parseLastSearchedData();
        $this->parser->getDiffTime();
        return $this->parser->difTime;
//
//        if ($time < 5) {
//
//            $this->botAnswer->showTimeIsOver();
//            die;
//            file_put_contents('time.txt', $time . "\n", FILE_APPEND);
//
//        }
    }

}