<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Models\WalletKit\AddressProperty;

class UtxoList
{
    public function __construct(private array $data)
    {
    }

    /** @return UtxoList */
    public function __invoke(): array
    {
        $utxoList = [];

        foreach ($this->data['utxos'] as $utxo) {
            $utxoList[] = new Utxo($utxo);
        }

        return $utxoList;
    }
}
