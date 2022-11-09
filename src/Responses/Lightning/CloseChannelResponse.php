<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

use UtxoOne\LndPhp\Models\Lightning\ChannelCloseUpdate;
use UtxoOne\LndPhp\Models\Lightning\PendingUpdate;

class CloseChannelResponse
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Close Pending
     * 
     * @return PendingUpdate
     */
    public function getClosePending(): PendingUpdate
    {
        return new PendingUpdate($this->data['close_pending']);
    }

    /**
     * Get Chan Close
     * 
     * @return ChannelCloseUpdate
     */
    public function getChanClose(): ChannelCloseUpdate
    {
        return new ChannelCloseUpdate($this->data['chan_close']);
    }
     
}
