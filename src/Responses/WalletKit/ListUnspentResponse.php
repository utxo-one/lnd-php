<?php

namespace UtxoOne\LndPhp\Responses\WalletKit;

use UtxoOne\LndPhp\Models\Lightning\UtxoList;

class ListUnspentResponse
{
    public function __construct(private array $data)
    {
    }

    public function getUtxos(): UtxoList
    {
        return new UtxoList($this->data['utxos']);
    }
}
