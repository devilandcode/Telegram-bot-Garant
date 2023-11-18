<?php

namespace App\Kernel\Parser;
use App\Models\UserModel;

class ParserUserData
{
    public string|null $id_telegram;
    public string|null $username;
    public string|null $chat_id;
    public string $callBackQuery;
    public string $idSearchTable;
    public string $idBuyer;
    public string $idSeller;
    public string $amount;
    public string $terms;
    public string $lastSearchedTime;
    public int $difTime;
    public string $idOfDeal;
    public string $buyerId;
    public string $sellerId;
    public string $amountOfDeal;
    public string $termsOfDeal;
    public string $isChanel;

    public function __construct(
        public \stdClass $data,
        public UserModel $userDBManager,
    )
    {
        $this->parsePhpInput();
        $this->parseLastSearchedData();
    }

    public function parsePhpInput(): self
    {
        $in = $this->data;
        $in = print_r($in, true);
        file_put_contents('inputs.txt', $in . "\n", FILE_APPEND);

        if ($this->data->callback_query->message->chat->type === 'channel') {
            $this->isChanel = true;
        } else {
            $this->isChanel = false;
        }

        if (isset($this->data->message) && isset($this->data->message->chat->username)) {
            $this->id_telegram = $this->data->message->chat->id;
            $this->username = $this->data->message->chat->username;
            $this->chat_id = $this->data->message->chat->id;
        } elseif (isset($this->data->callback_query) && isset($this->data->callback_query->chat->username)) {
            $this->id_telegram = $this->data->callback_query->message->chat->id;
            $this->username = $this->data->callback_query->message->chat->username;
            $this->chat_id = $this->data->callback_query->message->chat->id;
        } elseif (isset($this->data->callback_query) && isset($this->data->callback_query->message->chat->first_name)) {
            $this->id_telegram = $this->data->callback_query->message->chat->id;
            $this->username = $this->data->callback_query->message->chat->first_name;
            $this->chat_id = $this->data->callback_query->message->chat->id;
        } elseif (isset($this->data->message) && isset($this->data->message->chat->first_name)) {
            $this->id_telegram = $this->data->message->chat->id;
            $this->username = $this->data->message->chat->first_name;
            $this->chat_id = $this->data->message->chat->id;
        } else {
            $this->id_telegram = null;
            $this->username = null;
            $this->chat_id = null;
        }

        return $this;
    }

    /**
     * Parse data from search table
     * @param array|null $lastSearchedDataArray
     * @return mixed
     */
    public function parseLastSearchedData() : bool
    {
        $lastSearchedDataArray = $this->userDBManager->showLastSearchData($this->id_telegram);
        if ($lastSearchedDataArray != null) {
            $this->idSearchTable = $lastSearchedDataArray['id'];
            $this->idBuyer = $lastSearchedDataArray['id_buyer'];
            $this->idSeller = $lastSearchedDataArray['id_seller'];
            $this->amount = $this->checkIsIsset($lastSearchedDataArray, 'amount', null, null, 'none');
            $this->terms = $this->checkIsIsset($lastSearchedDataArray, 'text', null, null, 'none');
            $this->lastSearchedTime = $lastSearchedDataArray['time_in'];
            return true;
        }
        return false;
    }

    /**
     * Get the difference between the current time and the time of the last customer search
     * @return void
     */
    public function getDiffTime() : mixed
    {
        return $this->difTime = (time() - $this->lastSearchedTime) / 60;

    }

    public function parseDealData() : void
    {
        $dealDataArray = $this->userDBManager->getDataOfDeal(
            $this->getNumberOfDeal()
        );

        if ($dealDataArray != null) {
            $this->idOfDeal = $dealDataArray['id'];
            $this->buyerId = $dealDataArray['id_buyer'];
            $this->sellerId = $dealDataArray['id_seller'];
            $this->amountOfDeal = $dealDataArray['amount'];
            $this->termsOfDeal = $dealDataArray['text'];
        }
    }

    /**
     * Check is isset passed string
     * @param mixed $string
     * @param string|null $firstArg
     * @param string|null $secondArg
     * @param string|null $thirdArg
     * @param string|null $ifNot
     * @return mixed
     */
    public function checkIsIsset(mixed $string, string $firstArg = null, string $secondArg = null, string $thirdArg = null, string $ifNot = null): mixed
    {
        if (is_array($string) && $firstArg === null && $secondArg === null && $thirdArg === null) {
            return isset($string) ? $string : $ifNot;
        } elseif (is_array($string) && $firstArg !== null && $secondArg === null && $thirdArg === null) {
            return isset($string[$firstArg]) ? $string[$firstArg] : $ifNot;
        } elseif (is_array($string) && $firstArg !== null && $secondArg !== null && $thirdArg === null) {
            return isset($string[$firstArg][$secondArg]) ? $string[$firstArg][$secondArg] : $ifNot;
        } elseif (is_array($string) && $firstArg !== null && $secondArg !== null && $thirdArg !== null) {
            return isset($string[$firstArg][$secondArg][$thirdArg]) ? $string[$firstArg][$secondArg][$thirdArg] : $ifNot;
        } else {
            return isset($string) ? $string : $ifNot;
        }
    }

    public function getNumberOfDeal(): int
    {
        $text = $this->data->callback_query->message->text;
        return (int)(substr($text, 37, 8));
    }
}