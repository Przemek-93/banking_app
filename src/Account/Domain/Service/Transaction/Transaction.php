<?php

declare(strict_types=1);

namespace App\Account\Domain\Service\Transaction;

use App\Account\Domain\Entity\Account;
use App\Account\Domain\Entity\Payment;
use App\Account\Domain\Exception\InvalidAccountAndPaymentCurrencies;
use App\Account\Domain\Exception\InvalidTransactionType;
use App\Account\Domain\Repository\PaymentRepositoryInterface;
use App\Account\Domain\Service\Transaction\Transactions\Credit;
use App\Account\Domain\Service\Transaction\Transactions\Debit;

final class Transaction
{
    private array $transactions = [
        Credit::class,
        Debit::class,
    ];

    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
    ) {
    }

    public function executeTransaction(
        Account $account,
        Payment $payment,
    ): void {
        if ($account->getCurrency() !== $payment->getCurrency()) {
            throw new InvalidAccountAndPaymentCurrencies();
        }

        $transactionType = $payment->getTransactionType();

        foreach ($this->transactions as $transaction) {
            $transactionInstance = new $transaction($this->paymentRepository);

            if (true === $transactionInstance->support($transactionType)) {
                $transactionInstance->execute($account, $payment);

                return;
            }
        }

        throw new InvalidTransactionType($transactionType);
    }
}