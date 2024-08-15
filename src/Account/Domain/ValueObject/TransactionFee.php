<?php

declare(strict_types=1);

namespace App\Account\Domain\ValueObject;

final readonly class TransactionFee
{
    public const float DEBIT_FEE = 0.005;

    public function __construct(
        private float $amount,
    ) {
    }

    public function calculateDebitFee(): float
    {
        return $this->amount * self::DEBIT_FEE;
    }
}