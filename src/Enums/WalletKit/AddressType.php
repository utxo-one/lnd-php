<?php

namespace UtxoOne\LndPhp\Enums\WalletKit;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum AddressType: int
{
    use EnumHelper;

    case WITHNESS_PUBKEY_HASH = 0;
    case NESTED_PUBKEY_HASH = 1;
    case UNUSED_WITNESS_PUBKEY_HASH = 2;
    case UNUSED_NESTED_PUBKEY_HASH = 3;
    case TAPROOT_PUBKEY = 4;
    case UNUSED_TAPROOT_PUBKEY = 5;
}
