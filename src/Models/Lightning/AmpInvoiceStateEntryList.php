<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class AmpInvoiceStateEntryList {
    public function __construct(private array $data)
    {
    }

    /** @return AmpInvoiceStateEntry[] */
    public function __invoke(): array
    {
        $ampInvoiceStateEntries = [];

        foreach ($this->data as $ampInvoicestateEntry) {
            $ampInvoiceStateEntries[] = new AmpInvoiceStateEntry($ampInvoicestateEntry);
        }

        return $ampInvoiceStateEntries;
    }
}