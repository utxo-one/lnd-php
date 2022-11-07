<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class MacaroonPermission
{
    public function __construct(public array $data)
    {
    }

    /**
     * Get Entity
     * 
     * @return string
     */
    public function getEntity(): string
    {
        return $this->data['entity'];
    }

    /**
     * Get Action
     * 
     * @return string
     */
    public function getAction(): string
    {
        return $this->data['action'];
    }
}
