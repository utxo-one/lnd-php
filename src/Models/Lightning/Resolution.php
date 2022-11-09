<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Enums\Lightning\ResolutionOutcome;
use UtxoOne\LndPhp\Enums\Lightning\ResolutionType;

class Resolution
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Resolution Type
     * 
     * The type of output we are resolving.
     * 
     * @return ResolutionType
     */
    public function getResolutionType(): ResolutionType
    {
        return ResolutionType::fromValue($this->data['resolution_type']);
    }

    /**
     * Get Outcome
     * 
     * The outcome of our on chain action that resolved the outpoint.
     * 
     * @return ResolutionOutcome
     */
    public function getOutcome(): ResolutionOutcome
    {
        return ResolutionOutcome::fromValue($this->data['outcome']);
    }

    /**
     * Get Outpoint
     * 
     * The outpoint that was spent by the resolution.
     * 
     * @return OutPoint
     */
    public function getOutpoint(): OutPoint
    {
        return new OutPoint($this->data['outpoint']);
    }

    /**
     * Get Amount Sat
     * 
     * The amount of the output that was claimed by the resolution.
     * 
     * @return int
     */
    public function getAmountSat(): int
    {
        return $this->data['amount_sat'];
    }

    /**
     * Get Sweep Txid
     * 
     * The hex-encoded transaction ID of the sweep transaction that spent the output.
     * 
     * @return string
     */
    public function getSweepTxid(): string
    {
        return $this->data['sweep_txid'];
    }
}
