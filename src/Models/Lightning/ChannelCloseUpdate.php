<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class ChannelCloseUpdate
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Closing Txid
     * 
     * @return string
     */
    public function getClosingTxid(): string
    {
        return $this->data['closing_txid'];
    }

    /**
     * Get Success
     * 
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->data['success'];
    }

    /**
     * Is Successful
     * 
     * Adding an additional getter to stay consistent with other booleans.
     * 
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->data['success'];
    }
}
