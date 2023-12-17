<?php

namespace App\Controllers;

use App\Services\CallBackService\CallBackService;

class CallBackQueryController
{
    public function __construct(
        private CallBackService $service,
    )
    {
    }

    /** Reply to Telegram that the callbackQuery has been received by us.*/
    public function sendCallBackAnswerToTelegram()
    {
        $this->service->sendCallBackAnswer();
    }

    public function goToHomeCauseSellerNotExist()
    {
        $this->service->showStartMenu();
    }

    public function askToEnterAmountOfDeal()
    {
        $this->service->askBuyerToEnterAmountOfDeal();
    }

    public function cancelStartDeal()
    {
        $this->service->cancelAndGoStartMenu();
    }

    public function sendToSellerInvitation()
    {
        $numberOfDeal = $this->service->getNumberOfDealFromCallBackMessage();

        $this->service->sendInvitationToSeller($numberOfDeal);
        $this->service->notifyBuyerInvitatinWasSent();
    }

    public function cancelConfirmDeal()
    {
        $this->service->showBuyerThatHeCancledConfirm();
    }

    public function acceptInvitationFromBuyer()
    {
        $numberOfDeal = $this->service->getNumberOfDealFromCallBackMessage();

        $this->service->notifyBuyerAndAdminThatSellerAcceptInvitation($numberOfDeal);
        $this->service->showSellerThatHeAcceptTheInvitation();
    }

    public function cancelInvitationFromBuyer()
    {
        $numberOfDeal = $this->service->getNumberOfDealFromCallBackMessage();

        $this->service->showBuyerThatSellerCancelInvitation($numberOfDeal);
        $this->service->cancelAndGoStartMenu();
    }

    public function sendToAdminThatBuyerPaidToEscrow()
    {
        $numberOfDeal = $this->service->getNumberOfDealFromCallBackMessage();

        $this->service->showAdminThatBuyerPaidToEscrow($numberOfDeal);
        $this->service->showBuyerThatHeNotifiedAdminAdmoutPayingToEscrow();
    }

    public function showAdminAndSellerThatBuyerRefusedToPay()
    {
        $numberOfDeal = $this->service->getNumberOfDealFromCallBackMessage();

        $this->service->notifyAdminAndSellerThatBuyerRefusedToPay($numberOfDeal);
        $this->service->notifyBuyerThatHeRefusedToPay();
    }

    public function showBuyerAndSellerThatAdminGotTheMoney()
    {
        $numberOfDeal = $this->service->getNumberOfDealFromCallBackMessage();

        $this->service->startDealAndShowThatAdminGotMoney($numberOfDeal);
    }

    public function askAdminWhatToInputMessage()
    {
        $this->service->askAdminWhatMessageToSendToBot();
    }



}