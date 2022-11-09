<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class OutPoint
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Txid Bytes
     * 
     * Raw bytes representing the transaction id.
     * 
     * @return string
     */
    public function getTxidBytes(): string
    {
        return $this->data['txid_bytes'];
    }

    /**
     * Get Txid Str
     * 
     * Hex-encoded string representing the transaction id.
     * 
     * @return string
     */
    public function getTxidStr(): string
    {
        return $this->data['txid_str'];
    }

    /**
     * Get Output Index
     * 
     * The output index of the transaction.
     * 
     * @return int
     */
    public function getOutputIndex(): int
    {
        return $this->data['output_index'];
    }
}
