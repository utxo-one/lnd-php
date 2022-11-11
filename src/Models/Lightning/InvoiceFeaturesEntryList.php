<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class InvoiceFeaturesEntryList {
    public function __construct(private array $data)
    {
    }

    /** @return InvoiceFeaturesEntry[] */
    public function __invoke(): array
    {
        $invoiceFeaturesEntries = [];

        foreach ($this->data as $invoiceFeaturesEntry) {
            $invoiceFeaturesEntries[] = new InvoiceFeaturesEntry($invoiceFeaturesEntry);
        }

        return $invoiceFeaturesEntries;
    }
}