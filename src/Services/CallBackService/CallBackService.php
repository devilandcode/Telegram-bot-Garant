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
use App\Services\CallBackService\Handlers\GetDealModel;
use App\Services\CallBackService\Handlers\GetSearchModel;
use App\Services\CallBackService\Handlers\GetSellerModel;
use App\Services\DealRepository\DealRepository;
use App\Services\UserRepository\UserRepository;

class CallBackService
{
    public function __construct(
        private BotapiInterface $botApi,
        private Keyboards $botKeyboard,
        private Messages $botMessages,
        private UserRepository $userRepository,
        private DealRepository $dealRepository,
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

    public function showStartMenu()
    {
        $this->botKeyboard->startMenuTriggeredOnCallBack();
    }

    public function sendCallBackAnswer(): void
    {
        $this->botApi->sendCallBackAnswer('');
    }

    public function cancelAndGoStartMenu(): void
    {
        $this->botKeyboard->cancelAndStartHome();
    }

    public function sendInvitationToSeller(int $numberOfDeal): void
    {
        $searchModel = $this->getSearchModel($numberOfDeal);
        $buyerModel = $this->getBuyerModelByTelegramId($searchModel->idBuyer());

        $this->inviteSeller($searchModel, $buyerModel);
    }


    public function notifyBuyerInvitatinWasSent(): void
    {
        $this->botMessages->notifyBuyerInvitatinWasSent();
    }

    public function showBuyerThatHeCancledConfirm(): void
    {
        $this->botKeyboard->cancelAndStartHome();
    }

    public function notifyBuyerAndAdminThatSellerAcceptInvitation(int $numberOfDeal): void
    {
        $this->sendToBuyerThatSellerAcceptInvitation($numberOfDeal);
        $this->sendToAdminChannelThatSellerAcceptInvitation($numberOfDeal);
    }

    public function showSellerThatHeAcceptTheInvitation(): void
    {
        $this->botMessages->waitingWhenBuyerWillPay();
    }

    public function showBuyerThatSellerCancelInvitation(int $numberOfDeal): void
    {
        $searchModel = $this->getSearchModel($numberOfDeal);

        $this->botMessages->notifyToBuyerInvitationWasCanceled(
            $searchModel->idBuyer(),
            $searchModel->id(),
            $searchModel->idSeller()
        );
    }

    public function showAdminThatBuyerPaidToEscrow(int $numberOfDeal): void
    {
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botMessages->notifyAdminDealIsPaid(
            $this->config->get('bot.admin_chat_id'),
            $dealModel->id(),
            $dealModel->idBuyer()
        );
    }

    public function showBuyerThatHeNotifiedAdminAdmoutPayingToEscrow(): void
    {
        $this->botMessages->checkingBuyersTranssaction();
    }

    public function notifyAdminAndSellerThatBuyerRefusedToPay(int $numberOfDeal): void
    {
        $dealModel = $this->generateDealModel($numberOfDeal);

        $this->botMessages->showToAdminAndSellerThatBuyerRefusedToPay(
            $dealModel->idSeller(),
            $this->config->get('bot.admin_chat_id'),
            $dealModel->id(),
            $dealModel->idBuyer()
        );
    }

    public function notifyBuyerThatHeRefusedToPay(): void
    {
        $this->botMessages->showBuyerThatHeRefusedToPay();
    }

    public function askAdminWhatMessageToSendToBot()
    {
        $this->botMessages->askAdminToTextHisMessageToBot(
            $this->config->get('bot.admin_chat_id')
        );
    }

    public function startDealAndShowThatAdminGotMoney(int $numberOfDeal): void
    {
        $dealModel = $this->generateDealModel($numberOfDeal);

        if ($this->checkIsDealExistInDealTable($numberOfDeal)) {
            $this->notifyAdminThatDealIsAlreadyStarted();
        } else {
            $this->notifyBuyerSellerAdminGotMoneyAndStartedDeal($dealModel);
            $this->saveDataToDealTable($dealModel);
        }
    }

    private function notifyBuyerSellerAdminGotMoneyAndStartedDeal(Deal $dealModel): void
    {
        $this->showBuyerThatAdminGotMoneyAndStartedDeal($dealModel);
        $this->showSellerThatAdminGotMoneyAndStartedDeal($dealModel);
        $this->showAdminThatHeStartedTheDeal($dealModel);

    }

    private function checkIsDealExistInDealTable(string $numberOfDeal): bool
    {
        return $this->dealRepository->checkIsDealExist($numberOfDeal);
    }

    private function saveDataToDealTable(Deal $dealModel): void
    {
        $this->dealRepository->addDataToDealTable(
          $dealModel->id(),
          $dealModel->idBuyer(),
          $dealModel->usernameBuyer(),
          $dealModel->idSeller(),
          $dealModel->usernameSeller(),
          $dealModel->amount(),
          $dealModel->currency(),
          $dealModel->cryptoWallet(),
          $dealModel->resultAmount(),
          $dealModel->terms(),
        );
    }

    private function showBuyerThatAdminGotMoneyAndStartedDeal(Deal $dealModel): void
    {

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

    private function showSellerThatAdminGotMoneyAndStartedDeal(Deal $dealModel): void
    {
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

    private function showAdminThatHeStartedTheDeal(Deal $dealModel): void
    {
        $this->botMessages->notifyAdminThatHeStratedTheDeal(
            $this->config->get('bot.admin_chat_id'),
            $dealModel->id()
        );
    }
    private function notifyAdminThatDealIsAlreadyStarted(): void
    {
        $this->botMessages->showAdminThatDealAlreadyExist(
            $this->config->get('bot.admin_chat_id')
        );
    }


    private function sendToBuyerThatSellerAcceptInvitation(int $numberOfDeal): void
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

    private function sendToAdminChannelThatSellerAcceptInvitation(int $numberOfDeal): void
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
            $this->userRepository
        );
    }

    private function getSellerModelByTelegramId(string $sellerIdTelegram): Seller
    {
        return (new GetSellerModel())->get(
            $sellerIdTelegram,
            $this->config,
            $this->userRepository
        );
    }

    private function getSearchModel(int $numberOfDeal): Search
    {
        return (new GetSearchModel())->get(
            $numberOfDeal,
            $this->userRepository,
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

    public function getNumberOfDealFromCallBackMessage(): int
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