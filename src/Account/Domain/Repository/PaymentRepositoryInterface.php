<?php

declare(strict_types=1);

namespace App\Account\Domain\Repository;

use App\Account\Domain\Entity\Payment;

interface PaymentRepositoryInterface
{
    public function getDailyDebitTransactionCountByAccountId(string $accountId): int;

    public function save(Payment $payment): void;
}