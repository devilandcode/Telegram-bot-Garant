<?php

use App\App;

ini_set('log_errors', 'On');
ini_set('error_log', 'errors.log');
set_include_path(__DIR__);

// LOAD AUTOLOADER AND BOT PERSONAL DATA
require_once 'vendor/autoload.php';
require_once 'src/Init.php';

// CREATE APP INSTANCES
$app = new App(BOT_TOKEN);

// GET MESSAGES WHICH TEXTED IN BOT
$messageFromBot = $app->getMessageFromBot();

// GET MESSAGES FROM ADMIN CHANNEL
$messagesFromAdmin = $app->bot->getMessageFromAdminChannel();
file_put_contents('admin.txt', $messagesFromAdmin . "\n", FILE_APPEND);

//CHECK NEW USER, IF NEW -> INSERT TO DATABASE
$app->checkNewUser();

$input = $app->bot->getInputData();
$input = print_r($input, true);
file_put_contents('input.txt', $input . "\n", FILE_APPEND);

/**
 * SEND ANSWER TO TELEGRAM IF ISSET "CALLBACK_QUERY" ARRAY
 * (WHEN SOMEONE PRESSED THE INLINE BUTTON)
 */
if ($app->parser->callBackQuery !== 'Not Set') {
    $app->bot->sendCallBackAnswer('');
}

/**
 * RESPONSES TO INLINE BUTTONS PRESSES
 * @method askToEnterAmountOfDeal - Ask the buyer to text the amount(crypto) of deal
 * @method cancelAndStartHome - Cancel data entry and show the start menu
 * @method acceptOrCancelInvitation - Show the vendor a purchase request with all terms.
 * Then the seller can to submit or cancel this deal
 * @method notifySenderAboutSendingRequest - notify buyer that the request has been sent
 * @method waitingWhenCreaterOfDealWillPay - notify seller about waiting buyer's transaction
 * @method checkingYourTranssaction - notify buyer that his transaction is checking
 * @method notifyAdminDealIsPaid - notify admin channel that the buyer has pressed the button "is Paid"
 * @method cancelDealByBuyer - notify buyer that he has canceled the deal
 * @method notifyAllThatCreatorCancelDeal - notify buyer, seller and admin channel
 * that the buyer has canceled the deal
 */
if (isset($app->parser->callBackQuery)) {
    if ($app->parser->callBackQuery === 'startDeal') {
        $app->askToEnterAmountOfDeal();
    } elseif ($app->parser->callBackQuery === 'cancel') {
        $app->keyboards->cancelAndStartHome();
    } elseif ($app->parser->callBackQuery === 'confirmAndSendDeal') {
        $app->sendToSellerAcceptOrCancelInvitation();
        $app->messages->notifyBuyerAboutSendingRequest();
    } elseif ($app->parser->callBackQuery === 'acceptDeal') {
        $app->notifyBuyerAboutAcceptionOfDeal(BTC_WALLET, ETH_WALLET, ADMIN_CHAT_ID);
        $app->messages->waitingWhenBuyerWillPay();
    } elseif ($app->parser->callBackQuery === 'cancelInvitationBySeller') {
        $app->messages->cancelInvitationBySeller();
        $app->notifyBuyerThatSellerCancelInviation();
    } elseif ($app->parser->callBackQuery === 'paid') {
        $app->messages->checkingBuyersTranssaction();
        $app->notifyAdminDealIsPaid(ADMIN_CHAT_ID);
    } elseif ($app->parser->callBackQuery === 'cancelDealByBuyer') {
        $app->messages->cancelDealByBuyer();
        $app->notifyAllThatBuyerCancelDeal(ADMIN_CHAT_ID);
    } elseif (str_contains($app->parser->callBackQuery, 'adminAcceptMoney')) {
        $app->confirmAndStartDeal(ADMIN_CHAT_ID, $app->parser->callBackQuery);
    } elseif (str_contains($app->parser->callBackQuery, 'dealIsResolved')) {
        $app->markDealAsResolved(ADMIN_CHAT_ID, $app->parser->callBackQuery);
    }elseif ($app->parser->callBackQuery === 'sendMessageToBot') {
        $app->askAdminToTextHisMessageToBot(ADMIN_CHAT_ID);
    }
}

/**
 * RESPONSES TO BASEKEYBOARDS BUTTONS PASSES AND BUYER'S ENTERED MESSAGES
 * @method activeDeals - show user's active deals
 * @method explainHowToUseBot - show the bot's instruction
 * @method askIdToSearchUser - ask buyer to input seller's telegram user id
 * @method checkIsUserExist - check is the user exist in database
 * @method notExistSellerKeyboard - send inlinekeyboard with notification - "user not found"
 * @method existSellerKeyboard - notify - "user found" and send inline keyboard
 * with the ability to create a deal
 * @method addToSearchTable - insert buyer's and seller's search data into the search table
 * @method getDifferenceTime - counts the time difference between the last seller search and the current time.
 * In the bot it is worth 5 minutes to fill in the amount and terms of the deal,
 * otherwise the search is considered outdated and the procedure of creating a deal must be done again
 * @method addCryptoAmountToSeacrhTable - add passed by buyer amount of deal to search table
 * @method askTermsOfDeal - ask buyer to text terms of deal
 * @method addTermsOfDealToSearchTable - add passed by buyer terms of deal to search table
 * @method unknownCommand - notify about unknown command
 */
if ($messageFromBot === '/start') {
    $app->keyboards->start();
} elseif ($messageFromBot === '💀 Мой Профиль') {
    $app->myProfile();
} elseif ($messageFromBot === '🔥 Активные Сделки') {
    $app->messages->activeDeals();
} elseif ($messageFromBot === '📪 Служба Поддержки') {
    $app->messages->explainHowToUseBot();
} elseif ($messageFromBot === "👀 Поиск Пользователя") {
    $app->messages->askIdToSearchUser();
} elseif (is_numeric($messageFromBot) && strlen($messageFromBot) == 10) {
    if ($app->checkIsUserExist($messageFromBot) == null) {
        $app->keyboards->notExistSellerKeyboard();
    } else {
        $app->keyboards->existSellerKeyboard();
        $app->userManager->addToSearchTable($app->parser->id_telegram, $messageFromBot);
    }
} elseif ((str_contains($messageFromBot, "btc") !== false || str_contains(
            $messageFromBot,
            "eth"
        ) !== false) && ($app->getDifferenceTime() < 5)) {
    $app->userManager->addCryptoAmountToSeacrhTable($messageFromBot, $app->parser->idSearchTable);
    $app->messages->askTermsOfDeal();
} elseif ((str_contains($messageFromBot, "!Сделка") !== false || str_contains(
            $messageFromBot,
            "!сделка"
        ) !== false) && ($app->getDifferenceTime() < 5)) {
    $app->userManager->addTermsOfDealToSearchTable($messageFromBot, $app->parser->idSearchTable);
    $app->confrimTermsAndSendDeal();
} elseif (str_contains($app->bot->getMessageFromAdminChannel(), 'bot:') === true) {
    $app->mailBulkToBot();
}
elseif ($app->parser->parseInputInfo()->callBackQuery === 'Not Set') {
    $app->messages->unknownCommand();
}






