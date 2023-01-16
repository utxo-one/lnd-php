<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

class SendManyResponse
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
