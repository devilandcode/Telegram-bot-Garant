<?php

namespace App\Kernel\Controller;

use App\CryptoApi\CryptoApi;
use App\Kernel\Parser\ParserUserData;
use App\Keyboards;
use App\Messages;
use App\UserModel;

class Controller
{

    public function __construct(
        private Keyboards $botKeyboard,
        private Messages $botAnswer,
        private CryptoApi $cryptoApi,
        private UserModel $userDBManager,
        private ParserUserData $parser,
    )
    {
        $this->checkNewUser();
    }

    private Keyboards $botKeyboards;

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

    public function search()
    {

    }

    private function checkNewUser(): int|string
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

}