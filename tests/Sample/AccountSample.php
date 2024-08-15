<?php

declare(strict_types=1);

namespace App\Tests\Sample;

use App\Account\Domain\Entity\Account;
use App\Account\Domain\Enum\CurrencyEnum;
use Ramsey\Uuid\Uuid;

class AccountSample
{
    public static function create(
        float $balance = 0.0,
        CurrencyEnum $currency = CurrencyEnum::PLN,
    ): Account {
        return new Account(
            Uuid::uuid4()->toString(),
            $balance,
            $currency,
        );
    }
}