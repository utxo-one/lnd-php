<?php

namespace UtxoOne\LndPhp\Responses\WalletKit;

class NextAddrResponse
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
}
