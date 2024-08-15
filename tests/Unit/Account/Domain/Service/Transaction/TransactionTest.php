<?php

declare(strict_types=1);

namespace App\Tests\Unit\Account\Domain\Service\Transaction;

use App\Account\Domain\Enum\CurrencyEnum;
use App\Account\Domain\Enum\TransactionTypeEnum;
use App\Account\Domain\Exception\ExceedLimitOfDailyDebitTransaction;
use App\Account\Domain\Exception\InvalidAccountAndPaymentCurrencies;
use App\Account\Domain\Exception\NotEnoughFundsInAccount;
use App\Account\Domain\Service\Transaction\Transaction;
use App\Account\Infrastructure\Repository\InMemoryPaymentRepository;
use App\Tests\Sample\AccountSample;
use App\Tests\Sample\PaymentSample;
use App\Tests\Unit\UnitTestCase;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\Test;

final class TransactionTest extends UnitTestCase
{
    #[Test]
    public function testItShouldThrowNotTheSameCurrenciesException(): void
    {
        // Given
        $account = AccountSample::create(currency: CurrencyEnum::EUR);
        $payment = PaymentSample::create(accountId: $account->getId(), currency: CurrencyEnum::GBP);
        $transaction = new Transaction(new InMemoryPaymentRepository());

        // Then
        $this->expectException(InvalidAccountAndPaymentCurrencies::class);

        // When
        $transaction->executeTransaction($account, $payment);
    }

    #[Test]
    public function testCreditShouldIncreaseAccountBalance(): void
    {
        // Given
        $account = AccountSample::create();
        $accountBalanceBeforeCredit = $account->getBalance();
        $payment = PaymentSample::create(accountId: $account->getId(), amount: $amount = 1.1, transactionType: TransactionTypeEnum::CREDIT);
        $transaction = new Transaction(new InMemoryPaymentRepository());

        // When
        $transaction->executeTransaction($account, $payment);

        // Then
        $this->assertSame($accountBalanceBeforeCredit + $amount, $account->getBalance());
    }

    #[Test]
    public function testDebitShouldThrowExceedTransactionLimitException(): void
    {
        // Given
        $account = AccountSample::create();
        $payment = PaymentSample::create($account->getId());
        $existedPayments = $this->generateExistedPayments($account->getId(), random_int(3, 100));
        $transaction = new Transaction(new InMemoryPaymentRepository($existedPayments));

        // Then
        $this->expectException(ExceedLimitOfDailyDebitTransaction::class);

        // When
        $transaction->executeTransaction($account, $payment);
    }

    #[Test]
    public function testDebitShouldThrowNotEnoughFoundException(): void
    {
        // Given
        $account = AccountSample::create();
        $payment = PaymentSample::create(accountId: $account->getId(), amount: 1.1);
        $transaction = new Transaction(new InMemoryPaymentRepository());

        // Then
        $this->expectException(NotEnoughFundsInAccount::class);

        // When
        $transaction->executeTransaction($account, $payment);
    }

    #[Test]
    public function testDebitShouldDecreaseAccountBalance(): void
    {
        // Given
        $account = AccountSample::create(balance: 3.3);
        $accountBalanceBeforeDebit = $account->getBalance();
        $payment = PaymentSample::create(accountId: $account->getId(), amount: 1.1);
        $transaction = new Transaction(new InMemoryPaymentRepository());

        // When
        $transaction->executeTransaction($account, $payment);

        // Then
        $this->assertSame($accountBalanceBeforeDebit - $payment->getTotalDebitPaymentCost(), $account->getBalance());
    }

    #[Test]
    public function testDebitShouldDecreaseAccountBalanceAndInNextTransactionThrowLimitExceedException(): void
    {
        // Success path
        // Given
        $account = AccountSample::create();
        $accountBalanceBeforeDebit = $account->getBalance();
        $payment = PaymentSample::create($account->getId());
        $existedPayments = $this->generateExistedPayments($account->getId(), 2);
        $transaction = new Transaction(new InMemoryPaymentRepository($existedPayments));

        // When
        $transaction->executeTransaction($account, $payment);

        // Then
        $this->assertSame($accountBalanceBeforeDebit - $payment->getTotalDebitPaymentCost(), $account->getBalance());

        // Exceed the debit transactions limit path
        // Given
        $payment = PaymentSample::create($account->getId());

        // Then
        $this->expectException(ExceedLimitOfDailyDebitTransaction::class);

        // When
        $transaction->executeTransaction($account, $payment);
    }

    #[Test]
    public function testDebitShouldDecreaseAccountBalanceAndInNextTransactionThrowNotEnoughFundsException(): void
    {
        // Success path
        // Given
        $account = AccountSample::create(1.1);
        $accountBalanceBeforeDebit = $account->getBalance();
        $payment = PaymentSample::create(accountId: $account->getId(), amount: 1.0);
        $existedPayments = $this->generateExistedPayments($account->getId(), 2);
        $transaction = new Transaction(new InMemoryPaymentRepository($existedPayments));

        // When
        $transaction->executeTransaction($account, $payment);

        // Then
        $this->assertSame($accountBalanceBeforeDebit - $payment->getTotalDebitPaymentCost(), $account->getBalance());

        // Not enough funds path
        // Given
        $payment = PaymentSample::create(accountId: $account->getId(), amount: 1.0);

        // Then
        $this->expectException(NotEnoughFundsInAccount::class);

        // When
        $transaction->executeTransaction($account, $payment);
    }

    #[Test]
    public function testDebitShouldDecreaseBalanceAndInNextTransactionCreditShouldIncreaseBalance(): void
    {
        // Debit path
        // Given
        $account = AccountSample::create(1.1);
        $accountBalanceBeforeDebit = $account->getBalance();
        $payment = PaymentSample::create(accountId: $account->getId(), amount: 1.0);
        $transaction = new Transaction(new InMemoryPaymentRepository());

        // When
        $transaction->executeTransaction($account, $payment);

        // Then
        $this->assertSame($accountBalanceBeforeDebit - $payment->getTotalDebitPaymentCost(), $account->getBalance());

        // Credit path
        // Given
        $accountBalanceBeforeCredit = $account->getBalance();
        $payment = PaymentSample::create(accountId: $account->getId(), amount: $amount = 1.0, transactionType: TransactionTypeEnum::CREDIT);

        // When
        $transaction->executeTransaction($account, $payment);

        // Then
        $this->assertSame($accountBalanceBeforeCredit + $amount, $account->getBalance());
    }

    private function generateExistedPayments(
        string $accountId,
        int $count = 2,
        TransactionTypeEnum $transactionType = TransactionTypeEnum::DEBIT,
        DateTimeInterface $createdAt = new DateTimeImmutable(),
    ): array {
        $payments = [];
        for ($i = 0; $i < $count; $i++) {
            $payments[] = PaymentSample::create(
                accountId: $accountId,
                transactionType: $transactionType,
                createdAt: $createdAt,
            );
        }

        return $payments;
    }
}
