<?php

namespace App\Services\UsersService;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Models\Search;
use App\Services\UsersService\Handlers\IsTimeForCreateDealIsOver;
use App\Services\UsersService\Handlers\GetCryptoCurrencyOfDeal;
use App\Services\UsersService\Handlers\isKeywordExistInMessageFromBot;
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
        /** IsKeywordExistInMessageFromBot Handler*/
        return (new isKeywordExistInMessageFromBot())->check($messageFromBot);
    }

    public function hasDealKeyword()
    {

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
        $isTimeForCreateDealIsOver = (new IsTimeForCreateDealIsOver())
            ->check(
                $this->getSearchModel(),
                $this->config
            );

        if ($isTimeForCreateDealIsOver) {
            $this->botMessages->showTimeIsOver();
        }else {
            $cryptoCurrency = (new GetCryptoCurrencyOfDeal())->get($messageFromBot);
        }
    }


    private function isUserExistInUsersTable(string $telegramId): bool
    {
        return $this->repository->checkIsUserExistByTelegramId($telegramId);
    }

    private function getSearchModel(): Search
    {
        $telegramId = $this->botApi->phpInput()->message->from->id;
        $getBuyersLastSearchedArray = $this->repository->showLastSearchData($telegramId);

        /** Get Names of columns at search_history table*/
        $id = $this->config->get('database.search_name_of_primary_key');
        $idBuyer = $this->config->get('database.search_name_of_column_with_id_buyer');
        $idSeller = $this->config->get('database.search_name_of_column_with_id_seller');
        $amount = $this->config->get('database.search_name_of_column_with_crypto_amount');
        $terms = $this->config->get('database.search_name_of_column_with_terms_of_deal');
        $startTime = $this->config->get('database.search_name_of_column_with_start_search_time');

        /** @var Search */
        return new Search(
            $getBuyersLastSearchedArray[$id],
            $getBuyersLastSearchedArray[$idBuyer],
            $getBuyersLastSearchedArray[$idSeller],
            $getBuyersLastSearchedArray[$amount],
            $getBuyersLastSearchedArray[$terms],
            $getBuyersLastSearchedArray[$startTime]
        );
    }

}