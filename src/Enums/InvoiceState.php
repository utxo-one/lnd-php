<?php

namespace UtxoOne\LndPhp\Enums;

use UtxoOne\LndPhp\Traits\EnumHelper;

/** @todo Get the actual enums */
class InvoiceState
{
    use EnumHelper;

    public const OPEN = 'OPEN';
    public const SETTLED = 'SETTLED';
    public const CANCELED = 'CANCELED';
    public const ACCEPTED = 'ACCEPTED';
}