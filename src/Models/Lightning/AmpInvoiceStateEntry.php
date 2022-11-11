<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Enums\Lightning\AmpInvoiceState;

class AmpInvoiceStateEntry
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Key
     * 
     * @return string
     */
    public function getKey(): string
    {
        return $this->data['key'];
    }

    /**
     * Get Value
     * 
     * @return AmpInvoiceState
     */
    public function getValue(): AmpInvoiceState
    {
        return AmpInvoiceState::fromValue($this->data['value']);
    }
}
