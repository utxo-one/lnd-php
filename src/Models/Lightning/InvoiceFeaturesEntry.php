<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class InvoiceFeaturesEntry
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
     * @return Feature
     */
    public function getValue(): Feature
    {
        return new Feature($this->data['value']);
    }
}
