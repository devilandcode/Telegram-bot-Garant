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
use App\Services\CallBackService\Handlers\GetDealModel;
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
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();
        $searchModel = $this->getSearchModel($numberOfDeal);
        $buyerModel = $this->getBuyerModelByTelegramId($searchModel->idBuyer());

        $this->inviteSeller($searchModel, $buyerModel);
    }


    public function notifyBuyerInvitatinWasSent()
    {
        $this->botMessages->notifyBuyerInvitatinWasSent();
    }

    public function showBuyerThatHeCancledConfirm()
    {
        $this->botKeyboard->cancelAndStartHome();
    }

    public function notifyBuyerAboutAcceptionOfDeal()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();


    }


    private function getSearchModel(int $numberOfDeal): Search
    {
        return (new GetSearchModel())->get(
            $numberOfDeal,
            $this->repository,
            $this->config
        );
    }

    private function getBuyerModelByTelegramId(string $buyerIdTelegram): Buyer
    {
        return (new GetBuyerModel())->get(
            $buyerIdTelegram,
            $this->config,
            $this->repository
        );
    }

    private function getDealModel(int $numberOfDeal)
    {

    }

    private function getNumberOfDealFromCallBackMessage(): int
    {
        $text = $this->botApi->phpInput()->callback_query->message->text;
        $elemPos = mb_strpos($text,'№');
        $startPos = $elemPos + 2;
        $str = mb_substr($text, $startPos, 10);

        return (int)$str;
    }

    private function inviteSeller(Search $searchModel, Buyer $buyerModel): void
    {
        $this->botKeyboard->showInvitationKeyboardToSeller(
            $searchModel->idSeller(),
            $searchModel->id(),
            $searchModel->idBuyer(),
            $buyerModel->username(),
            $searchModel->amount(),
            $searchModel->terms()
        );
    }
}