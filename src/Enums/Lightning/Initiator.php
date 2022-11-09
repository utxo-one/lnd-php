<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum Initiator: int
{
    use EnumHelper;
    
    case INITIATOR_UNKNOWN = 0;
    case INITIATOR_LOCAL = 1;
    case INITIATOR_REMOTE = 2;
    case INITIATOR_BOTH = 3;
}