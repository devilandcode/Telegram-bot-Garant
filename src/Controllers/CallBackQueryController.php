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
        $this->service->sendInvitationToSeller();
        $this->service->notifyBuyerInvitatinWasSent();
    }

    public function cancelConfirmDeal()
    {
        $this->service->showBuyerThatHeCancledConfirm();
    }

    public function acceptInvitationFromBuyer()
    {
        $this->service->notifyBuyerAndAdminThatSellerAcceptInvitation();
        $this->service->showSellerThatHeAcceptTheInvitation();
    }

    public function cancelInvitationFromBuyer()
    {
        $this->service->showBuyerThatSellerCancelInvitation();
        $this->service->cancelAndGoStartMenu();
    }



}