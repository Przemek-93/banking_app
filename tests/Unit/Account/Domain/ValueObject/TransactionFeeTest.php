<?php

declare(strict_types=1);

namespace App\Tests\Unit\Account\Domain\ValueObject;

use App\Account\Domain\ValueObject\TransactionFee;
use App\Tests\Unit\UnitTestCase;
use PHPUnit\Framework\Attributes\Test;
use TypeError;

final class TransactionFeeTest extends UnitTestCase
{
    #[Test]
    public function testShouldSuccessCalculateDebitFee(): void
    {
        // Given
        $amount = 1.1;
        $transactionFee = new TransactionFee($amount);

        // When
        $result = $transactionFee->calculateDebitFee();

        // Then
        $this->assertSame($amount * TransactionFee::DEBIT_FEE, $result);
    }

    public function testShouldThrowTypeError(): void
    {
        //Then
        $this->expectException(TypeError::class);

        // When
        new TransactionFee( 'a');
    }
}
