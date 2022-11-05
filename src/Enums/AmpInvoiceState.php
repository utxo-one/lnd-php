<?php

namespace UtxoOne\LndPhp\Enums;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum AmpInvoiceState: string
{
    use EnumHelper;

    case OPEN = 'OPEN';
    case SETTLED = 'SETTLED';
    case CANCELED = 'CANCELED';
    case ACCEPTED = 'ACCEPTED';
}