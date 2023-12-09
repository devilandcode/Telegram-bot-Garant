<?php

namespace App\Services\UsersService;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Models\Search;
use App\Services\UsersService\Handlers\GetSearchModel;
use App\Services\UsersService\Handlers\IsDealKeywordExistInMessageFromBot;
use App\Services\UsersService\Handlers\IsTimeForCreateDealIsOver;
use App\Services\UsersService\Handlers\GetCryptoCurrencyOfDeal;
use App\Services\UsersService\Handlers\isAmountKeywordExistInMessageFromBot;
use App\Services\UsersService\Repositories\UserRepository;

class UserService
{

    public function __construct(
        private BotapiInterface $botApi,
        private ConfigInterface $config,
        private Keyboards $botKeyboards,
        private Messages $botMessages,
        private UserRepository $repository
    )
    {
    }

    public function isTelegramId(string $messageFromBot)
    {
        if (is_numeric($messageFromBot) && strlen($messageFromBot) === 10) {
            return true;
        }

        return false;
    }

    public function hasAmountKeywords(string $messageFromBot): bool
    {
        /** IsAmountKeywordExistInMessageFromBot Handler*/
        return (new isAmountKeywordExistInMessageFromBot())->check($messageFromBot);
    }

    public function hasDealKeyword(string $messageFromBot)
    {
        /** IsDealKeywordExistInMessageFromBot Handler*/
        return (new IsDealKeywordExistInMessageFromBot())->check($messageFromBot);
    }

    public function handleSellerId(string $sellerId): void
    {

        if ($this->isUserExistInUsersTable($sellerId)) {

            $this->botKeyboards->showKeyboardUserExist();
            $this->repository->addToSearchTable(
                $this->botApi->phpInput()->message->from->id,
                $sellerId
            );

        }else {
            $this->botKeyboards->showGoBackKeyboard();
        }

    }

    public function handleAmmoutOfDeal(string $messageFromBot)
    {
        $searchModel = $this->getSearchModel();

        $isTimeForCreateDealIsOver = (new IsTimeForCreateDealIsOver())
            ->check(
                $searchModel,
                $this->config
            );

        if ($isTimeForCreateDealIsOver) {
            $this->botMessages->showTimeIsOver();
        }else {
            $cryptoCurrency = (new GetCryptoCurrencyOfDeal())->get($messageFromBot);
            $amountOfDeal = (float)trim($messageFromBot);

            $this->repository->addCryptoAmountToSeacrhTable(
                $amountOfDeal,
                $cryptoCurrency,
                $searchModel->id()
            );

            $this->askBuyerToInputTermsOfDeal();
        }
    }

    public function handleTermsOfDeal(string $messageFromBot)
    {
        $searchModel = $this->getSearchModel();

        $isTimeForCreateDealIsOver = (new IsTimeForCreateDealIsOver())
            ->check(
                $searchModel,
                $this->config
            );

        if ($isTimeForCreateDealIsOver) {
            $this->botMessages->showTimeIsOver();
        }else {
            $termsOfDeal = trim($messageFromBot);

            $this->repository->addTermsOfDealToSearchTable(
                $termsOfDeal,
                $searchModel->id(),
            );

            $this->askBuyerToConfirmDealData();
        }
    }


    private function isUserExistInUsersTable(string $telegramId): bool
    {
        return $this->repository->checkIsUserExistByTelegramId($telegramId);
    }

    private function getSearchModel(): Search
    {
        return (new GetSearchModel())->get(
            $this->botApi,
            $this->repository,
            $this->config
        );
    }
    private function askBuyerToInputTermsOfDeal(): void
    {
        $this->botMessages->askTermsOfDeal();
    }

    private function askBuyerToConfirmDealData()
    {
        $searchModel = $this->getSearchModel();

        $this->botKeyboards->sendConfirmationKeyboard(
            $searchModel->id(),
            $searchModel->idBuyer(),
            $searchModel->idSeller(),
            $searchModel->amount(),
            $searchModel->terms()
        );
    }

}