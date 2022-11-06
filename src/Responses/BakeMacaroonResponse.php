<?php

namespace UtxoOne\LndPhp\Responses;

class BakeMacaroonResponse
{
    public function __construct(private array $data)
    {
    }

    public function __invoke(): string
    {
        return $this->data['macaroon'];
    }
}
