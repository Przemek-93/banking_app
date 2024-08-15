<?php

declare(strict_types=1);

namespace App\Account\Domain\Service\Transaction\Transactions;

use App\Account\Domain\Entity\Account;
use App\Account\Domain\Entity\Payment;
use App\Account\Domain\Enum\TransactionTypeEnum;

interface TransactionInterface
{
    public function support(TransactionTypeEnum $transactionType): bool;

    public function execute(Account $account, Payment $payment): void;
}