<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

class SendCoinsResponse
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Txid
     * 
     * @return string
     */
    public function getTxid(): string
    {
        return $this->data['txid'];
    }
     
}
