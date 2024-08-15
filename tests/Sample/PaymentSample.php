<?php

declare(strict_types=1);

namespace App\Tests\Sample;

use App\Account\Domain\Entity\Payment;
use App\Account\Domain\Enum\CurrencyEnum;
use App\Account\Domain\Enum\TransactionTypeEnum;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;

class PaymentSample
{
    public static function create(
        string $accountId,
        CurrencyEnum $currency = CurrencyEnum::PLN,
        float $amount = 0.0,
        TransactionTypeEnum $transactionType = TransactionTypeEnum::DEBIT,
        DateTimeInterface $createdAt = new DateTimeImmutable(),
    ): Payment {
        return new Payment(
            Uuid::uuid4()->toString(),
            $accountId,
            $currency,
            $amount,
            $transactionType,
            $createdAt,
        );
    }
}