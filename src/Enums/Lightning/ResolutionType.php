<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum ResolutionType: int {

    use EnumHelper;
    
    case TYPE_UNKNOWN = 0;
    case ANCHOR = 1;
    case INCOMING_HTLC = 2;
    case OUTGOING_HTLC = 3;
    case COMMIT = 4;
}