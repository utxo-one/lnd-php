<?php

namespace UtxoOne\LndPhp\Enums;

use UtxoOne\LndPhp\Traits\EnumHelper;

class InvoiceHtlcState
{
    use EnumHelper;
    
    public const ACCEPTED = 'ACCEPTED';
    public const SETTLED = 'SETTLED';
    public const IN_FLIGHT = 'IN_FLIGHT';
}