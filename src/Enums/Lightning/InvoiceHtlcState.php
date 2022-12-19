<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum InvoiceHtlcState: int
{
    use EnumHelper;

    case ACCEPTED = 0;
    case SETTLED = 1;
    case CANCELED = 2;
}
