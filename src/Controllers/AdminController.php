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

    public function sendMessageToAllUsers()
    {
        $users = $this->adminService->getAllTelegramIdOfAllUsers();

        $this->adminService->messageToAllUsers();
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
//    public function makeBulkMailing(string $messageFromAdmin): void
//    {
//        $allUsersIdArray = $this->userDBManager->getAllUsersID();
//        $messageFromAdmin = trim($messageFromAdmin);
//        $messageToBot = mb_substr($messageFromAdmin, 4);
//        foreach($allUsersIdArray as $key => $value) {
//            try {
//                $this->botAnswer->mailToBot($value['id_telegram'], $messageToBot);
//            } catch (\Exception $e) {
//                $errorData = $e->getMessage() ?? 'Not $e';
//            }
//        }
//        $this->botAnswer->mailToAdminSuccess($this->config->get('bot.admin_chat_id'));
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