<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class Route
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Total Time Lock
     *
     * @return int
     */
    public function getTotalTimeLock(): int
    {
        return $this->data['total_time_lock'];
    }

    /**
     * Get Total Fees
     *
     * @return int
     */
    public function getTotalFees(): string
    {
        return $this->data['total_fees'];
    }

    /**
     * Get Total Amount
     *
     * @return int
     */
    public function getTotalAmount(): string
    {
        return $this->data['total_amt'];
    }

    /**
     * Get Hops
     *
     * @return Hops[]
     */
    public function getHops(): HopList
    {
        return new HopList($this->data['hops']);
    }

    /**
     * Get Total Fees Msat
     *
     * @return int
     */
    public function getTotalFeesMsat(): int
    {
        return $this->data['total_fees_msat'];
    }

    /**
     * Get Total Amount Msat
     *
     * @return int
     */
    public function getTotalAmountMsat(): int
    {
        return $this->data['total_amt_msat'];
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
