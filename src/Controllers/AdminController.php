<?php

namespace App\Controllers;

use App\Services\AdminService\AdminService;

class AdminController
{
    public function __construct(
        private AdminService $adminService
    )
    {
    }

    public function analyze()
    {
        if ($this->adminService->isMessageToAllUsers()) {
            $this->sendMessageToAllUsers();
            return;
        }
    }


    public function sendMessageToAllUsers()
    {
        $users = $this->adminService->getAllTelegramIdOfAllUsers();
        $message = $this->adminService->getMessageToAllUsers();

        $this->adminService->messageToAllUsers($users, $message);
        $this->adminService->showAdminThatMessageWasSent();
    }


//    public function markDealComplete(): void
//    {
//        $this->checkIsMarkDealLikeComplete();
//
//        $this->parser->parseDataFromDealTable();
//
//        $this->botAnswer->notifyAdminDealResolved($this->config->get('bot.admin_chat_id'),$this->parser->idOfDeal);
//        $this->botAnswer->notifyBuyerDealResolved($this->parser->buyerId,$this->parser->idOfDeal);
//        $this->botAnswer->notifySellerDealResolved($this->parser->sellerId,$this->parser->idOfDeal);
//
//        $this->dealDBManager->markDealIsResolved($this->parser->idOfDeal);
//    }
//
//    public function askWhatMessageToSend(): void
//    {
//        $this->botAnswer->askAdminToTextHisMessageToBot($this->config->get('bot.admin_chat_id'));
//    }
//
//    private function checkIsMarkDealLikeComplete()
//    {
//        $dealNumber = $this->parser->getNumberOfDeal();
//        $columnValue= $this->dealDBManager->checkIsDealMarkLikeResolved($dealNumber);
//
//        if ($columnValue['is_resolved'] == self::DEAL_IS_RESOLVED) {
//            $this->botAnswer->dealIsAlredyComplete($this->config->get('bot.admin_chat_id'));
//            die;
//        }
//    }
}