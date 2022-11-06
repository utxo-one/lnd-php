<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class BatchOpenChannel
{
    public function __construct(
        private string $nodePubkey,
        private int $localFundingAmount,
        private int $pushSat,
        private bool $private,
        private int $minHtlcMsat,
        private string $remoteCsvDelay,
        private string $closeAddress,
        private string $pendingChanId,
    )
    {
    }

}
