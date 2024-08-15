<?php

declare(strict_types=1);

namespace App\Account\Domain\Service\Transaction\Transactions;

use App\Account\Domain\Entity\Account;
use App\Account\Domain\Entity\Payment;
use App\Account\Domain\Enum\TransactionTypeEnum;
use App\Account\Domain\Exception\ExceedLimitOfDailyDebitTransaction;
use App\Account\Domain\Exception\NotEnoughFundsInAccount;

final class Debit extends AbstractTransaction
{
    public function support(TransactionTypeEnum $transactionType): bool
    {
        return $transactionType === TransactionTypeEnum::DEBIT;
    }

    public function execute(Account $account, Payment $payment): void
    {
        if ($account->getBalance() < $payment->getTotalDebitPaymentCost()) {
            throw new NotEnoughFundsInAccount();
        }

        $debitTransactionsCount = $this->paymentRepository->getDailyDebitTransactionCountByAccountId($account->getId());
        if (false === $account->isUnderDailyDebitTransactionLimit($debitTransactionsCount)) {
            throw new ExceedLimitOfDailyDebitTransaction();
        }

        $account->decreaseBalance($payment->getTotalDebitPaymentCost());

        $this->paymentRepository->save($payment);
    }
}