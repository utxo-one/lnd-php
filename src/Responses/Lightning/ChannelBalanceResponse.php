<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

use UtxoOne\LndPhp\Enums\Lightning\ChannelCommitmentType;
use UtxoOne\LndPhp\Models\Lightning\Amount;

class ChannelBalanceResponse
{

    public function __construct(private array $data)
    {
    }

    /**
     * Get Balance
     * 
     * The total balance of all open channels denominated in satoshis.
     * 
     * @deprecated
     * 
     * @return int
     */
    public function getBalance(): string
    {
        return $this->data['balance'];
    }

    /**
     * Get Pending Open Balance
     * 
     * The total balance of all pending channels denominated in satoshis.
     * 
     * @deprecated
     * 
     * @return int
     */
    public function getPendingOpenBalance(): string
    {
        return $this->data['pending_open_balance'];
    }

    /**
     * Get Local Balance
     * 
     * Sum of channels local balances.
     * 
     * @return Amount 
     */
    public function getLocalBalance(): Amount
    {
        return new Amount($this->data['local_balance']);
    }

    /**
     * Get Remote Balance
     * 
     * Sum of channels remote balances.
     * 
     * @return Amount 
     */
    public function getRemoteBalance(): Amount
    {
        return new Amount($this->data['remote_balance']);
    }

    /**
     * Get Pending Open Local Balance
     * 
     * Sum of channels local balances in pending channels.
     * 
     * @return Amount 
     */
    public function getPendingOpenLocalBalance(): Amount
    {
        return new Amount($this->data['pending_open_local_balance']);
    }

    /**
     * Get Pending Open Remote Balance
     * 
     * Sum of channels remote balances in pending channels.
     * 
     * @return Amount 
     */
    public function getPendingOpenRemoteBalance(): Amount
    {
        return new Amount($this->data['pending_open_remote_balance']);
    }

    /**
     * Get Unsettled Local Balance
     * 
     * Sum of unsettled local balances.
     * 
     * @return Amount 
     */
    public function getUnsettledLocalBalance(): Amount

    {
        return new Amount($this->data['unsettled_local_balance']);
    }

    /**
     * Get Unsettled Remote Balance
     * 
     * Sum of unsettled remote balances.
     * 
     * @return Amount 
     */
    public function getUnsettledRemoteBalance(): Amount
    {
        return new Amount($this->data['unsettled_remote_balance']);
    }
}
