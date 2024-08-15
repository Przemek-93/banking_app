<?php

declare(strict_types=1);

namespace App\Account\Domain\Enum;

enum TransactionTypeEnum: string
{
    case CREDIT = 'CREDIT';

    case DEBIT = 'DEBIT';
}
