<?php

declare(strict_types=1);

namespace App\Account\Domain\Entity;

use App\Account\Domain\Enum\CurrencyEnum;

final class Account
{
    public const int DAILY_DEBIT_TRANSACTION_LIMIT = 3;

    public function __construct(
        private string $id,
        private float $balance,
        private CurrencyEnum $currency,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function increaseBalance(float $amount): void
    {
        $this->balance += $amount;
    }

    public function decreaseBalance(float $amount): void
    {
        $this->balance -= $amount;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function isUnderDailyDebitTransactionLimit(int $debitTransactionsCount): bool
    {
        return $debitTransactionsCount < self::DAILY_DEBIT_TRANSACTION_LIMIT;
    }
}