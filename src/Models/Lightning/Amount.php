<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class Amount
{
    /**
     * @var array
     */
    private array $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get Sat
     *
     * The amount of satoshis in this amount.
     *
     * @return string
     */
    public function getSat(): string
    {
        return $this->data['sat'];
    }

    /**
     * Get Msat
     *
     * The amount of millisatoshis in this amount.
     *
     * @return string
     */
    public function getMsat(): string
    {
        return $this->data['msat'];
    }
}
