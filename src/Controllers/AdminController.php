<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;

class AdminController extends Controller
{
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
}