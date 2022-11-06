<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class PendingUpdate
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Txid
     * 
     * @return string
     */
    public function getTxid(): string
    {
        return $this->data['txid'];
    }

    /**
     * Get Output Index
     * 
     * @return string
     */
    public function getOutputIndex(): string
    {
        return $this->data['output_index'];
    }
}