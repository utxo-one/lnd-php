<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

use UtxoOne\LndPhp\Models\Lightning\PendingUpdateList;

class BatchOpenChannelResponse
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Pending Channels
     * 
     * @return array
     */
    public function getPendingChannels(): PendingUpdateList
    {
        return new PendingUpdateList($this->data['pending_channels']);
    }
}