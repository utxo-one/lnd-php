<?php

namespace UtxoOne\LndPhp\Responses\WalletKit;

use UtxoOne\LndPhp\Models\WalletKit\AccountWithAddressesList;

class ListAddressesResponse
{
    public function __construct(private array $data)
    {
    }

    public function getAccountWithAddresses(): AccountWithAddressesList
    {
        return new AccountWithAddressesList($this->data['account_with_addresses']);
    }
}
