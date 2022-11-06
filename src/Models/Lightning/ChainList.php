<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class ChainList {
    public function __construct(private array $data)
    {
    }

    /** @return Chain[] */
    public function __invoke(): array
    {
        $chains = [];

        foreach ($this->data['chains'] as $chain) {
            $chains[] = new Chain($chain);
        }

        return $chains;
    }
}