<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Repository;

use App\Account\Domain\Entity\Payment;
use App\Account\Domain\Enum\TransactionTypeEnum;
use App\Account\Domain\Repository\PaymentRepositoryInterface;
use DateTimeImmutable;

class InMemoryPaymentRepository implements PaymentRepositoryInterface
{
    /** @param array<Payment> $payments */
    public function __construct(
        private array $payments = [],
    ) {
    }

    public function getDailyDebitTransactionCountByAccountId(string $accountId): int
    {
        $currentDate = (new DateTimeImmutable())->format('Y-m-d');

        $dailyDebitTransaction = array_filter(
            $this->payments,
            static function(Payment $payment) use ($accountId, $currentDate) {
                return $payment->getAccountId() === $accountId && $payment->getTransactionType() === TransactionTypeEnum::DEBIT &&
                       $payment->getCreatedAt()->format('Y-m-d') === $currentDate;
            }
        );

        return count($dailyDebitTransaction);
    }

    public function save(Payment $payment): void
    {
        $this->payments[] = $payment;
    }
}