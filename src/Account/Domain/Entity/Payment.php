<?php

declare(strict_types=1);

namespace App\Account\Domain\Entity;

use App\Account\Domain\Enum\CurrencyEnum;
use App\Account\Domain\Enum\TransactionTypeEnum;
use App\Account\Domain\ValueObject\TransactionFee;
use DateTimeImmutable;
use DateTimeInterface;

final class Payment
{
    public function __construct(
        private string $id,
        private string $accountId,
        private CurrencyEnum $currency,
        private float $amount,
        private TransactionTypeEnum $transactionType,
        private DateTimeInterface $createdAt = new DateTimeImmutable(),
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTotalDebitPaymentCost(): float
    {
        return $this->amount + (new TransactionFee($this->amount))->calculateDebitFee();
    }

    public function getTransactionType(): TransactionTypeEnum
    {
        return $this->transactionType;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}