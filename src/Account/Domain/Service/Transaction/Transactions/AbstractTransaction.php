<?php

declare(strict_types=1);

namespace App\Account\Domain\Service\Transaction\Transactions;

use App\Account\Domain\Repository\PaymentRepositoryInterface;

abstract class AbstractTransaction implements TransactionInterface
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository,
    ) {
    }
}