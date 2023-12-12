<h1 align="center"><strong>Bot Garant</strong></h1>

## About
This is a framework for a telegram guarantor bot, adding new functionality does not require a change in the code. You just add new code or delete the code you don't need.
This bot uses its own cryptocurrency wallets and then after the buyer deposits escrow account in the admin panel you need to manually confirm the transaction, but you can independently connect remote cryptocurrency wallet service by api.

Working principle:
The buyer and seller are added to the bot. Then the buyer opens the "search for seller" menu, enters his telegram user id, if the bot finds him, it offers to open a deal. The buyer enters the amount of the deal, the terms of the deal and sends it to the buyer for confirmation. The seller checks everything. If the seller clicked "confirm", the buyer receives a notification that the seller has agreed and is given a cryptocurrency wallet (escrow account) for replenishment. After payment, the buyer clicks "paid". After that, the administrator receives a notification that the transaction has been paid. After checking for payment - the administrator clicks "start deal" and the deal is started, the seller and the buyer are notified.
The admin panel has the following functionality:
- Start deal
- Send message to buyer
- Send message to Seller
- Send message to bot (to all users)
- Finish deal

## Instruments

- PHP 8.2
- guzzle http

## Get started
1) **Clone** this repository
2) **Composer install**
3) **Set** in config/bot.php your *bot_token* and *admin_chat_id* (u need to create private channel, your admin panel will be there)
4) **Create Database and tables** (SQL dumps are attached to this repository, you can name the columns of the table in your own way and show their names in config/database.php.)
3) **Set Webhook**
   
   *For testing locally use ngrok*
   - Install ngrok
   - Open your project directory via terminal and write - "ngrok http 80"
   - Get your ngrok https link
   
    For set webhook write in ur browser this link (example):
https://api.telegram.org/botBOT_TOKEN/setWebhook?url=https://61b2-46-53-245-38.ngrok-free.app/index.php
    WHERE: 
    - BOT_TOKEN - get ur bot token from botfather bot
    - https://61b2-46-53-245-38.ngrok-free.app - an example of ngrok https link
   
   Response from tg : {"ok":true,"result":true,"description":"Webhook is already set"}
    
    Done!

4) 
