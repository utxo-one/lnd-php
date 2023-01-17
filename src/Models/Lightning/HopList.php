<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class HopList
{
    public function __construct(private array $data)
    {
    }

    /** @return Hop[] */
    public function __invoke(): array
    {
        return $this->all();
    }

    /** @return Hop[] */
    public function all(): array
    {
        $hops = [];

        foreach ($this->data as $hop) {
            $hops[] = new Hop($hop);
        }

        return $hops;
    }
}
