<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class InvoiceHtlcList {
    public function __construct(private array $data)
    {
    }

    /** @return InvoiceHtlc[] */
    public function __invoke(): array
    {
        $invoiceHtlcs = [];

        foreach ($this->data as $invoiceHtlc) {
            $invoiceHtlcs[] = new InvoiceHtlc($invoiceHtlc);
        }

        return $invoiceHtlcs;
    }
}