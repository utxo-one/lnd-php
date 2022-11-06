<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

/** @todo Get the actual enums */
enum ChannelCommitmentType: string
{
    use EnumHelper;

    case ANCHORS = 'ANCHORS';
    case LEGACY = 'LEGACY';
}