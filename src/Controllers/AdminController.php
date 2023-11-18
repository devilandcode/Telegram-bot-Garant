<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;

class AdminController extends Controller
{
    const DEAL_IS_RESOLVED = '1';
    public function startByAdmin(): void
    {
        $this->parser->parseDealData();

        $this->botKeyboard->notifyBuyerAdminRecievedMoney(
            $this->parser->buyerId,
            $this->parser->idOfDeal,
            $this->parser->amountOfDeal,
            $this->userDBManager->getUserInfoById($this->parser->buyerId)['username'],
            $this->parser->sellerId,
            $this->userDBManager->getUserInfoById($this->parser->sellerId)['username'],
            $this->parser->termsOfDeal
        );

        $this->botKeyboard->notifySellerAdminRecievedMoney(
            $this->parser->sellerId,
            $this->parser->idOfDeal,
            $this->parser->amountOfDeal,
            $this->parser->buyerId,
            $this->userDBManager->getUserInfoById($this->parser->buyerId)['username'],
            $this->userDBManager->getUserInfoById($this->parser->sellerId)['username'],
            $this->parser->termsOfDeal
        );

        $this->dealDBManager->addDataToConfirmedDealTable(
            $this->parser->idOfDeal,
            $this->parser->buyerId,
            $this->parser->sellerId,
            $this->parser->amountOfDeal,
            $this->parser->termsOfDeal
        );

        $this->botAnswer->notifyAdminDealConfirmed(
            $this->config->get('bot.admin_chat_id'),
            $this->parser->idOfDeal
        );
    }

    public function markDealComplete(): void
    {
        $this->checkIsMarkDealLikeComplete();

        $this->parser->parseDataFromDealTable();

        $this->botAnswer->notifyAdminDealResolved($this->config->get('bot.admin_chat_id'),$this->parser->idOfDeal);
        $this->botAnswer->notifyBuyerDealResolved($this->parser->buyerId,$this->parser->idOfDeal);
        $this->botAnswer->notifySellerDealResolved($this->parser->sellerId,$this->parser->idOfDeal);

        $this->dealDBManager->markDealIsResolved($this->parser->idOfDeal);
    }

    public function askWhatMessageToSend(): void
    {
        $this->botAnswer->askAdminToTextHisMessageToBot($this->config->get('bot.admin_chat_id'));
    }

    public function makeBulkMailing(string $messageFromAdmin): void
    {
        $allUsersIdArray = $this->userDBManager->getAllUsersID();
        $messageFromAdmin = trim($messageFromAdmin);
        $messageToBot = mb_substr($messageFromAdmin, 4);
        foreach($allUsersIdArray as $key => $value) {
            try {
                $this->botAnswer->mailToBot($value['id_telegram'], $messageToBot);
            } catch (\Exception $e) {
                $errorData = $e->getMessage() ?? 'Not $e';
            }
        }
        $this->botAnswer->mailToAdminSuccess($this->config->get('bot.admin_chat_id'));
    }

    private function checkIsMarkDealLikeComplete()
    {
        $dealNumber = $this->parser->getNumberOfDeal();
        $columnValue= $this->dealDBManager->checkIsDealMarkLikeResolved($dealNumber);

        if ($columnValue['is_resolved'] == self::DEAL_IS_RESOLVED) {
            $this->botAnswer->dealIsAlredyComplete($this->config->get('bot.admin_chat_id'));
            die;
        }
    }
}