<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

use UtxoOne\LndPhp\Models\Lightning\Route;

class SendResponse
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Payment Error
     *
     * @return string
     */
    public function getPaymentError(): string
    {
        return $this->data['payment_error'];
    }

    /**
     * Get Payment Preimage
     *
     * @return string
     */
    public function getPaymentPreimage(): string
    {
        return $this->data['payment_preimage'];
    }

    /**
     * Get Payment Route
     *
     * @return Route
     */
    public function getPaymentRoute(): Route
    {
        return new Route($this->data['payment_route']);
    }

    /**
     * Get Payment Hash
     *
     * @return string
     */
    public function getPaymentHash(): string
    {
        return $this->data['payment_hash'];
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
