<?php

declare(strict_types=1);

namespace App\Account\Domain\Service\Transaction\Transactions;

use App\Account\Domain\Entity\Account;
use App\Account\Domain\Entity\Payment;
use App\Account\Domain\Enum\TransactionTypeEnum;

final class Credit extends AbstractTransaction
{
    public function support(TransactionTypeEnum $transactionType): bool
    {
        return $transactionType === TransactionTypeEnum::CREDIT;
    }

    public function execute(Account $account, Payment $payment): void
    {
        $account->increaseBalance($payment->getAmount());

        $this->paymentRepository->save($payment);
    }
}