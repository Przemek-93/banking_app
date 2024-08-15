<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use DomainException;

class ExceedLimitOfDailyDebitTransaction extends DomainException
{
    public const string MESSAGE = 'The daily amount of debit transactions has been exceeded!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}