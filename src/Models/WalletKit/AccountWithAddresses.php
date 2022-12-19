<?php

namespace UtxoOne\LndPhp\Models\WalletKit;

use UtxoOne\LndPhp\Enums\WalletKit\AddressType;

class AccountWithAddresses
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Account Name
     * 
     * The name used to identify the account.
     * 
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->data['account_name'];
    }

    /**
     * Get Address Type
     * 
     * 	The type of addresses the account supports.
     * 
     * @return AddressType
     */
    public function getAddressType(): AddressType
    {
        return AddressType::fromValue($this->data['address_type']);
    }

    /**
     * Get Deriviation Path
     * 
     * The derivation path corresponding to the account public key. 
     * This will always be empty for the default imported account in which single public keys are imported into.
     * 
     * @return string
     */
    public function getDerivationPath(): string
    {
        return $this->data['derivation_path'];
    }

    /**
     * Get Addresses
     * 
     * List of address, its type internal/external & balance. 
     * Note that the order of addresses will be random and not according to the derivation index, 
     * since that information is not stored by the underlying wallet.
     * 
     * @return AddressPropertyList
     */
    public function getAddresses(): AddressPropertyList
    {
        return new AddressPropertyList($this->data['addresses']);
    }
}
