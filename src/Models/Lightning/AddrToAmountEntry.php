<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class AddrToAmountEntry
{
    public function __construct(public string $addr, public int $amount)
    {
    }

    public function getAddr(): string
    {
        return $this->addr;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            $this->addr => $this->amount,
        ];
    }
}
