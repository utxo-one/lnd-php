<?php

namespace UtxoOne\LndPhp\Models;

class PendingUpdateList {
    public function __construct(private array $data)
    {
    }

    /** @return PendingUpdate[] */
    public function __invoke(): array
    {
        $pendingUpdates = [];

        foreach ($this->data['chains'] as $pendingUpdate) {
            $pendingUpdates[] = new PendingUpdate($pendingUpdate);
        }

        return $pendingUpdates;
    }
}