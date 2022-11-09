<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum InvoiceState: int {

    use EnumHelper;

    case OPEN = 0;
    case SETTLED = 1;
    case CANCELED = 2;
    case ACCEPTED = 3;
}