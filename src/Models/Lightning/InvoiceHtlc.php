<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Enums\InvoiceHtlcState;

class InvoiceHtlc
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Chain Id
     * 
     * @return string
     */
    public function getChainId(): string
    {
        return $this->data['chain_id'];
    }

    /**
     * Get AmtMsat
     * 
     * @return int
     */
    public function getAmtMsat(): int
    {
        return $this->data['amt_msat'];
    }

    /**
     * Get AcceptHeight
     * 
     * @return int
     */
    public function getAcceptHeight(): int
    {
        return $this->data['accept_height'];
    }

    /**
     * Get AcceptTime
     * 
     * @return int
     */
    public function getAcceptTime(): int
    {
        return $this->data['accept_time'];
    }

    /**
     * Get ResolveTime
     * 
     * @return int
     */
    public function getResolveTime(): int
    {
        return $this->data['resolve_time'];
    }

    /**
     * Get ExpiryHeight
     * 
     * @return int
     */
    public function getExpiryHeight(): int
    {
        return $this->data['expiry_height'];
    }

    /**
     * Get State
     * 
     * @return InvoiceHtlcState
     */
    public function getState(): InvoiceHtlcState
    {
        return InvoiceHtlcState::fromValue($this->data['state']);
    }

    /**
     * Get MppTotalAmtMsat
     * 
     * @return int
     */
    public function getMppTotalAmtMsat(): int
    {
        return $this->data['mpp_total_amt_msat'];
    }

    /**
     * Get CustomRecords
     * 
     * @return array
     */
    public function getCustomRecords(): array
    {
        return $this->data['custom_records'];
    }

    /**
     * Get RouteHints
     * 
     * @return array
     */
    public function getRouteHints(): array
    {
        return $this->data['route_hints'];
    }
}

