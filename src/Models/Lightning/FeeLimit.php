<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class FeeLimit
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Fixed
     *
     * @return int
     */
    public function getFixed(): int
    {
        return $this->data['fixed'];
    }

    /**
     * Get Fixed Msat
     *
     * @return int
     */
    public function getFixedMsat(): int
    {
        return $this->data['fixed_msat'];
    }

    /**
     * Get Percent
     *
     * @return int
     */
    public function getPercent(): int
    {
        return $this->data['percent'];
    }

    /**
     * To Array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
