<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class Hop
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Chan ID
     *
     * @return string
     */
    public function getChanId(): string
    {
        return $this->data['chan_id'];
    }

    /**
     * Get Chan Capacity
     *
     * @return string
     */
    public function getChanCapacity(): string
    {
        return $this->data['chan_capacity'];
    }

    /**
     * Get Amount To Forward
     *
     * @return string
     */
    public function getAmountToForward(): string
    {
        return $this->data['amt_to_forward'];
    }

    /**
     * Get Fee
     *
     * @return string
     */
    public function getFee(): string
    {
        return $this->data['fee'];
    }

    /**
     * Get Expiry
     *
     * @return int
     */
    public function getExpiry(): int
    {
        return $this->data['expiry'];
    }

    /**
     * Get Amount To Forward Msat
     *
     * @return string
     */
    public function getAmountToForwardMsat(): string
    {
        return $this->data['amt_to_forward_msat'];
    }

    /**
     * Get Fee Msat
     *
     * @return string
     */
    public function getFeeMsat(): string
    {
        return $this->data['fee_msat'];
    }

    /**
     * Get Pub Key
     *
     * @return string
     */
    public function getPubKey(): string
    {
        return $this->data['pub_key'];
    }

    /**
     * Get Tlv Payload
     *
     * @return bool
     */
    public function getTlvPayload(): bool
    {
        return $this->data['tlv_payload'];
    }

    /**
     * Get Mpp Record
     *
     * @return array
     */
    public function getMppRecord(): array
    {
        return $this->data['mpp_record'];
    }

    /**
     * Get Custom Records
     *
     * @return array
     */
    public function getCustomRecords(): array
    {
        return $this->data['custom_records'];
    }

    /**
     * Get Metadata
     *
     * @return string
     */
    public function getMetadata(): string
    {
        return $this->data['metadata'];
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
