<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum ClosureType: int {

    use EnumHelper;
    
    case COOPERATIVE_CLOSE = 0;
    case LOCAL_FORCE_CLOSE = 1;
    case REMOTE_FORCE_CLOSE = 2;
    case BREACH_CLOSE = 3;
    case FUNDING_CANCELED = 4;
    case ABANDONED = 5;
}