<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class Feature {

    public function __construct(private array $data)
    {
    }

    /**
     * Get Name
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->data['name'];
    }

    /**
     * Is Required
     * 
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->data['is_required'];
    }

    /**
     * Is Known
     * 
     * @return bool
     */
    public function isKnown(): bool
    {
        return $this->data['is_known'];
    }
}