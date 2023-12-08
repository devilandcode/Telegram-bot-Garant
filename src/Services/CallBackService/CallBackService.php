<?php

namespace App\Services\CallBackService;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Models\Buyer;
use App\Models\Search;
use App\Services\CallBackService\Handlers\GetBuyerModel;
use App\Services\CallBackService\Handlers\GetCryptoPrice;
use App\Services\CallBackService\Handlers\GetSearchModel;
use App\Services\UsersService\Repositories\UserRepository;

class CallBackService
{
    public function __construct(
        private BotapiInterface $botApi,
        private Keyboards $botKeyboard,
        private Messages $botMessages,
        private UserRepository $repository,
        private ConfigInterface $config,
    )
    {
    }

    public function askBuyerToEnterAmountOfDeal(): void
    {
        $arrayOfCryptoPrices = (new GetCryptoPrice())->getCryptoCurrencyPrice();
        extract($arrayOfCryptoPrices);

        $this->botMessages->askAmountOfDeal($btcPrice, $ethPrice, $usdtPrice);
    }

    public function sendCallBackAnswer(): void
    {
        $this->botApi->sendCallBackAnswer('');
    }

    public function cancelAndGoStartMenu()
    {
        $this->botKeyboard->cancelAndStartHome();
    }

    public function sendInvitationToSeller()
    {
        $searchModel = $this->getSearchModel();
        $buyerModel = $this->getBuyerModelByTelegramId($searchModel->idBuyer());

        $this->botKeyboard->showInvitationKeyboardToSeller(
            $searchModel->idSeller(),
            $searchModel->id(),
            $searchModel->idBuyer(),
            $buyerModel->username(),
            $searchModel->amount(),
            $searchModel->terms()
        );
    }

    public function notifyBuyerInvitatinWasSent()
    {
        $this->botMessages->notifyBuyerInvitatinWasSent();
    }


    private function getSearchModel(): Search
    {
        return (new GetSearchModel())->get(
            $this->botApi,
            $this->repository,
            $this->config
        );
    }

    private function getBuyerModelByTelegramId(string $idTelegram): Buyer
    {
        return (new GetBuyerModel())->get(
            $idTelegram,
            $this->config,
            $this->repository
        );
    }
}