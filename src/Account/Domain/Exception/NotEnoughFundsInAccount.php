<?php

declare(strict_types=1);

namespace App\Account\Domain\Exception;

use DomainException;

class NotEnoughFundsInAccount extends DomainException
{
    public const string MESSAGE = 'Not enough funds in the account!';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}