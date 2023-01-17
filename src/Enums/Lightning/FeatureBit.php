<?php

namespace UtxoOne\LndPhp\Enums\Lightning;

use UtxoOne\LndPhp\Traits\EnumHelper;

enum FeatureBit: int
{
    use EnumHelper;

    case DATALOSS_PROTECT_REQ = 0;
    case DATALOSS_PROTECT_OPT = 1;
    case INITIAL_ROUTING_SYNC = 3;
    case UPFRONT_SHUTDOWN_SCRIPT_REQ = 4;
    case UPFRONT_SHUTDOWN_SCRIPT_OPT = 5;
    case GOSSIP_QUERIES_REQ = 6;
    case GOSSIP_QUERIES_OPT = 7;
    case TLV_ONION_REQ = 8;
    case TLV_ONION_OPT = 9;
    case EXT_GOSSIP_QUERIES_REQ = 10;
    case EXT_GOSSIP_QUERIES_OPT = 11;
    case STATIC_REMOTE_KEY_REQ = 12;
    case STATIC_REMOTE_KEY_OPT = 13;
    case PAYMENT_ADDR_REQ = 14;
    case PAYMENT_ADDR_OPT = 15;
    case MPP_REQ = 16;
    case MPP_OPT = 17;
    case WUMBO_CHANNELS_REQ = 18;
    case WUMBO_CHANNELS_OPT = 19;
    case ANCHORS_REQ = 20;
    case ANCHORS_OPT = 21;
    case ANCHORS_ZERO_FEE_HTLC_REQ = 22;
    case ANCHORS_ZERO_FEE_HTLC_OPT = 23;
    case AMP_REQ = 30;
    case AMP_OPT = 31;
}
