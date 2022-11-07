<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

class BakeMacaroonResponse
{
    public function __construct(private array $data)
    {
    }

    public function __invoke(): string
    {
        return $this->data['macaroon'];
    }

    public function getMacaroon(): string
    {
        return $this->data['macaroon'];
    }

    public function __toString()
    {
        return $this->data['macaroon'];
    }
}
