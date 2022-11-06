<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class ChannelPoint {
    public function __construct(private array $data)
    {
    }

    /**
     * Get FundingTxidBytes
     * 
     * @return string
     */
    public function getFundingTxidBytes(): string
    {
        return $this->data['funding_txid_bytes'];
    }

    /**
     * Get FundingTxidStr
     * 
     * @return string
     */
    public function getFundingTxidStr(): string
    {
        return $this->data['funding_txid_str'];
    }

    /**
     * Get OutputIndex
     * 
     * @return int
     */
    public function getOutputIndex(): int
    {
        return $this->data['output_index'];
    }
}