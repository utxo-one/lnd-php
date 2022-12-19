<?php

namespace UtxoOne\LndPhp\Models\WalletKit;

class AddressProperty
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Address
     * 
     * @return string
     */
    public function getAddr(): string
    {
        return $this->data['addr'];
    }

    /**
     * Is Internal
     * 
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->data['is_internal'];
    }

    /**
     * Get Balance
     * 
     * @return string
     */
    public function getBalance(): string
    {
        return $this->data['balance'];
    }
}
