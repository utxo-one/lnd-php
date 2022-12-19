<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Enums\WalletKit\AddressType;

class Utxo
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Address Type
     * 
     * The type of address
     * 
     * @return AddressType
     */
    public function getAddressType(): AddressType
    {
        return AddressType::fromValue($this->data['address_type']);
    }

    /**
     * Get Address
     * 
     * The address of the utxo
     * 
     * @return string
     */
    public function getAddress(): string
    {
        return $this->data['address'];
    }

    /**
     * Get Amount Sat
     * 
     * The amount of the utxo in satoshis
     * 
     * @return string
     */
    public function getAmountSat(): string
    {
        return $this->data['amount_sat'];
    }


    /**
     * Get PK Script
     * 
     * The pk script of the utxo
     * 
     * @return string
     */
    public function getPkScript(): string
    {
        return $this->data['pk_script'];
    }

    /**
     * Get Outpoint
     * 
     * The outpoint of the utxo
     * 
     * @return OutPoint
     */
    public function getOutpoint(): OutPoint
    {
        return new OutPoint($this->data['outpoint']);
    }

    /**
     * Get Confirmations
     * 
     * The number of confirmations of the utxo
     * 
     * @return string
     */
    public function getConfirmations(): string
    {
        return $this->data['confirmations'];
    }
}
