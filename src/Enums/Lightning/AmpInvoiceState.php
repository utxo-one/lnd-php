<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

/** @todo Get the actual enums */
enum AmpInvoiceState: string
{
    use EnumHelper;

    case OPEN = 'OPEN';
    case SETTLED = 'SETTLED';
    case CANCELED = 'CANCELED';
    case ACCEPTED = 'ACCEPTED';
}