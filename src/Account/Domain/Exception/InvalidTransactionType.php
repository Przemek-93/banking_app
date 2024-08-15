<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use App\Account\Domain\Enum\TransactionTypeEnum;
use DomainException;

class InvalidTransactionType extends DomainException
{
    public const string MESSAGE = 'Invalid transaction type [%s]!';

    public function __construct(TransactionTypeEnum $transactionType)
    {
        parent::__construct(sprintf(self::MESSAGE, $transactionType->value));
    }
}