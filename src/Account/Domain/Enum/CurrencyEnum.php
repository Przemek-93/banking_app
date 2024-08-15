<?php

declare(strict_types=1);

namespace App\Account\Domain\Enum;

enum CurrencyEnum: string
{
    case PLN = 'PLN';

    case EUR = 'EUR';

    case USD = 'USD';

    case GBP = 'GBP';
}
