<?php

namespace App\Services\CallBackService;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\HTTP\BotapiInterface;
use App\Keyboards\Keyboards;
use App\Messages\Messages;
use App\Models\Buyer;
use App\Models\Deal;
use App\Models\Search;
use App\Models\Seller;
use App\Services\CallBackService\Handlers\GetBuyerModel;
use App\Services\CallBackService\Handlers\GetCryptoPrice;
use App\Services\CallBackService\Handlers\GetSearchModel;
use App\Services\CallBackService\Handlers\GetDealModel;
use App\Services\CallBackService\Handlers\GetSellerModel;
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

    public function notifyBuyerAndAdminThatSellerAcceptInvitation()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();

        $this->sendToBuyerThatSellerAcceptInvitation($numberOfDeal);
        $this->sendToAdminChannelThatSellerAcceptInvitation($numberOfDeal);
    }

    public function showSellerThatHeAcceptTheInvitation()
    {
        $this->botMessages->waitingWhenBuyerWillPay();
    }

    public function showBuyerThatSellerCancelInvitation()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();
        $searchModel = $this->getSearchModel($numberOfDeal);

        $this->botMessages->notifyToBuyerInvitationWasCanceled(
            $searchModel->idBuyer(),
            $searchModel->id(),
            $searchModel->idSeller()
        );
    }

    public function showAdminThatBuyerPaidToEscrow()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botMessages->notifyAdminDealIsPaid(
            $this->config->get('bot.admin_chat_id'),
            $dealModel->id(),
            $dealModel->idBuyer()
        );
    }

    public function showBuyerThatHeNotifiedAdminAdmoutPayingToEscrow()
    {
        $this->botMessages->checkingBuyersTranssaction();
    }

    public function notifyAdminAndSellerThatBuyerRefusedToPay()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botMessages->showToAdminAndSellerThatBuyerRefusedToPay(
            $dealModel->idSeller(),
            $this->config->get('bot.admin_chat_id'),
            $dealModel->id(),
            $dealModel->idBuyer()
        );
    }

    public function notifyBuyerThatHeRefusedToPay()
    {
        $this->botMessages->showBuyerThatHeRefusedToPay();
    }

    public function showBuyerThatAdminGotMoneyAndStartedDeal()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botKeyboard->notifyBuyerAdminReceivedMoney(
            $dealModel->idBuyer(),
            $dealModel->id(),
            $dealModel->amount(),
            $dealModel->currency(),
            $dealModel->usernameBuyer(),
            $dealModel->idSeller(),
            $dealModel->usernameSeller(),
            $dealModel->terms()
        );
    }

    public function showSellerThatAdminGotMoneyAndStartedDeal()
    {
        $numberOfDeal = $this->getNumberOfDealFromCallBackMessage();
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botKeyboard->notifySellerAdminReceivedMoney(
            $dealModel->idSeller(),
            $dealModel->id(),
            $dealModel->amount(),
            $dealModel->currency(),
            $dealModel->idBuyer(),
            $dealModel->usernameBuyer(),
            $dealModel->usernameSeller(),
            $dealModel->terms()
        );
    }


    private function sendToBuyerThatSellerAcceptInvitation(int $numberOfDeal)
    {
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botKeyboard->showBuyerThatSellerAcceptInvitationKeyboard(
            $dealModel->idBuyer(),
            $dealModel->id(),
            $dealModel->amount(),
            $dealModel->currency(),
            $dealModel->resultAmount(),
            $dealModel->idBuyer(),
            $dealModel->usernameBuyer(),
            $dealModel->idSeller(),
            $dealModel->usernameSeller(),
            $dealModel->terms(),
            $dealModel->cryptoWallet(),
        );

    }

    private function sendToAdminChannelThatSellerAcceptInvitation(int $numberOfDeal)
    {
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botKeyboard->sendToAdminChannelDataOfDeal(
            $this->config->get('bot.admin_chat_id'),
            $dealModel->id(),
            $dealModel->amount(),
            $dealModel->currency(),
            $dealModel->resultAmount(),
            $dealModel->idBuyer(),
            $dealModel->usernameBuyer(),
            $dealModel->idSeller(),
            $dealModel->usernameSeller(),
            $dealModel->terms(),
            $dealModel->cryptoWallet(),
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

    private function getSellerModelByTelegramId(string $sellerIdTelegram): Seller
    {
        return (new GetSellerModel())->get(
            $sellerIdTelegram,
            $this->config,
            $this->repository
        );
    }

    private function getSearchModel(int $numberOfDeal): Search
    {
        return (new GetSearchModel())->get(
            $numberOfDeal,
            $this->repository,
            $this->config
        );
    }

    private function getDealModel(Buyer $buyerModel, Seller $sellerModel, Search $searchModel): Deal
    {
        return (new GetDealModel())->get(
            $buyerModel,
            $sellerModel,
            $searchModel,
            $this->config
        );

    }

    private function generateDealModel(int $numberOfDeal): Deal
    {
        $searchModel = $this->getSearchModel($numberOfDeal);
        $buyerModel = $this->getBuyerModelByTelegramId($searchModel->idBuyer());
        $sellerModel = $this->getSellerModelByTelegramId($searchModel->idSeller());

        return $this->getDealModel($buyerModel, $sellerModel, $searchModel);
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
            $searchModel->currency(),
            $searchModel->terms()
        );
    }
}