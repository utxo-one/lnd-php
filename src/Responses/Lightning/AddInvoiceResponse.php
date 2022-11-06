<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

class AddInvoiceResponse
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get R Hash
     * 
     * @return string
     */
    public function getRHash(): string
    {
        return $this->data['r_hash'];
    }

    /**
     * Get Payment Request
     *
     * @return string
     */
    public function getPaymentRequest(): string
    {
        return $this->data['payment_request'];
    }
    
    /**
     * Get Add Index
     *
     * @return int
     */
    public function getAddIndex(): int
    {
        return $this->data['add_index'];
    }

    /**
     * Get Payment Addr
     *
     * @return string
     */
    public function getPaymentAddr(): string
    {
        return $this->data['payment_addr'];
    }
}
