<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum ResolutionOutcome: int
{
    use EnumHelper;
    
    case OUTCOME_UNKNOWN = 0;
    case CLAIMED = 1;
    case UNCLAIMED = 2;
    case ABANDONED = 3;
    case FIRST_STAGE = 4;
    case TIMEOUT = 5;
}