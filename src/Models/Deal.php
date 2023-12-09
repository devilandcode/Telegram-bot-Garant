<?php

namespace App\Models;

class Deal
{
    public function __construct(
        private string $id,
        private string $idBuyer,
        private string $usernameBuyer,
        private string $idSeller,
        private string $usernameSeller,
        private string|null $amount,
        private string|null $currency,
        private string|null $resultAmount,
        private string|null $terms,
        private string|null $cryptoWallet,
    )
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function idBuyer(): string
    {
        return $this->idBuyer;
    }

    public function idSeller(): string
    {
        return $this->idSeller;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function terms(): string
    {
        return $this->terms;
    }

    /**
     * @return string
     */
    public function usernameBuyer(): string
    {
        return $this->usernameBuyer;
    }

    /**
     * @return string
     */
    public function usernameSeller(): string
    {
        return $this->usernameSeller;
    }

    /**
     * @return string|null
     */
    public function currency(): ?string
    {
        return $this->currency;
    }

    /**
     * @return string|null
     */
    public function resultAmount(): ?string
    {
        return $this->resultAmount;
    }

    /**
     * @return string|null
     */
    public function cryptoWallet(): ?string
    {
        return $this->cryptoWallet;
    }
}