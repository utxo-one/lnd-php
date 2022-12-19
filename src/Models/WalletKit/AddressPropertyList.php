<?php

namespace UtxoOne\LndPhp\Models\WalletKit;

class AddressPropertyList
{
    public function __construct(private array $data)
    {
    }

    /** @return AddressProperty[] */
    public function __invoke(): array
    {
        $addressProperties = [];

        foreach ($this->data as $addressProperty) {
            $addressProperties[] = new AddressProperty($addressProperty);
        }

        return $addressProperties;
    }
}
