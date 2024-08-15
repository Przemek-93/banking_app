<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use DomainException;

class InvalidAccountAndPaymentCurrencies extends DomainException
{
    public const string MESSAGE = 'Account and Payment currencies must be the same!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}