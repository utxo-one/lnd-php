<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class Chain
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Chain
     * 
     * @return string
     */
    public function getChain(): string
    {
        return $this->data['chain'];
    }

    /**
     * Get Network
     * 
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->data['network'];
    }
}
