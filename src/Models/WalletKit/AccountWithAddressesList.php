<?php

namespace UtxoOne\LndPhp\Models\WalletKit;

use UtxoOne\LndPhp\Enums\WalletKit\AddressType;

class AccountWithAddressesList
{
    public function __construct(private array $data)
    {
    }

    public function __invoke()
    {
        $accountWithAddresses = [];

        foreach ($this->data as $accountWithAddress) {
            $accountWithAddresses[] = new AccountWithAddresses($accountWithAddress);
        }

        return $accountWithAddresses;
    }
}
