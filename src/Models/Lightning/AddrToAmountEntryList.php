<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class AddrToAmountEntryList
{
    public function __construct(private array $data)
    {
    }

    public function toArray(): array
    {
        $addrToAmountEntries = [];

        foreach ($this->data as $addrToAmountEntry) {
            $addrToAmountEntries[$addrToAmountEntry->getAddr()] = $addrToAmountEntry->getAmount();
        }

        return $addrToAmountEntries;
    }
}
